/*!
 * charts.js — theme-aware Chart.js layer for NovaCart Admin.
 *
 * Every chart on every page is declared in HTML as:
 *   <div class="chart-box h-300"><canvas data-chart="revenueTrend"></canvas></div>
 * and matched to a spec in SPECS below. Nothing is hard-coded per page, so a
 * chart can be reused by adding one canvas.
 *
 * Colours are read from the CSS custom properties in style.css at build time
 * and again on `nc:themechange`, so the light/dark toggle restyles every chart
 * without duplicating a palette in JavaScript.
 *
 * Laravel note: each spec reads from `window.DB`. Swap those reads for the
 * props your Blade view passes in and the chart code stays identical.
 */
(function (window, document) {
  'use strict';

  if (!window.Chart) {
    if (window.console) console.warn('[charts] Chart.js not loaded — charts disabled.');
    return;
  }

  /* =======================================================================
   * 1. Design tokens
   * ===================================================================== */

  var CATEGORICAL = [
    '#6366f1', '#0ea5e9', '#8b5cf6', '#22c55e', '#f59e0b',
    '#ec4899', '#14b8a6', '#f97316', '#3b82f6', '#a3e635',
    '#e879f9', '#fb7185',
  ];

  function tokens() {
    var cs = getComputedStyle(document.documentElement);
    var read = function (name, fallback) {
      var v = cs.getPropertyValue(name);
      return v && v.trim() ? v.trim() : fallback;
    };
    var rgb = function (name, fallback) {
      return read(name, fallback).replace(/\s+/g, '');
    };

    return {
      dark: document.documentElement.getAttribute('data-bs-theme') === 'dark',
      text: read('--nc-text', '#101828'),
      muted: read('--nc-text-muted', '#667085'),
      tertiary: read('--nc-text-tertiary', '#98a2b3'),
      border: read('--nc-border', '#e4e8f0'),
      grid: read('--nc-border', '#e4e8f0'),
      surface: read('--nc-surface', '#ffffff'),
      surface2: read('--nc-surface-2', '#f8fafc'),
      primary: read('--nc-primary', '#6366f1'),
      success: read('--nc-success', '#12855a'),
      warning: read('--nc-warning', '#b54708'),
      danger: read('--nc-danger', '#d92d20'),
      info: read('--nc-info', '#0e7090'),
      primaryRgb: rgb('--nc-primary-rgb', '99,102,241'),
      successRgb: rgb('--nc-success-rgb', '18,133,90'),
      warningRgb: rgb('--nc-warning-rgb', '181,71,8'),
      dangerRgb: rgb('--nc-danger-rgb', '217,45,32'),
      infoRgb: rgb('--nc-info-rgb', '14,112,144'),
      font: "'Inter Variable', Inter, system-ui, -apple-system, 'Segoe UI', sans-serif",
      series: CATEGORICAL,
    };
  }

  function rgba(triplet, alpha) {
    return 'rgba(' + triplet + ', ' + alpha + ')';
  }

  /** Vertical fill used by area charts. */
  function areaGradient(context, triplet, topAlpha, bottomAlpha) {
    var chart = context.chart;
    var area = chart.chartArea;
    if (!area) return rgba(triplet, topAlpha || 0.22);
    var g = chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
    g.addColorStop(0, rgba(triplet, topAlpha == null ? 0.28 : topAlpha));
    g.addColorStop(1, rgba(triplet, bottomAlpha == null ? 0.01 : bottomAlpha));
    return g;
  }

  /* =======================================================================
   * 2. Chart.js defaults
   * ===================================================================== */

  function applyDefaults(T) {
    Chart.defaults.font.family = T.font;
    Chart.defaults.font.size = 11;
    Chart.defaults.font.weight = 500;
    Chart.defaults.color = T.muted;
    Chart.defaults.borderColor = T.grid;
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;
    Chart.defaults.animation.duration = 620;
    Chart.defaults.animation.easing = 'easeOutQuart';
    Chart.defaults.plugins.legend.display = false;
    Chart.defaults.plugins.tooltip.backgroundColor = T.dark ? 'rgba(23,32,45,.96)' : 'rgba(16,24,40,.94)';
    Chart.defaults.plugins.tooltip.titleColor = T.dark ? '#e8eef6' : '#ffffff';
    Chart.defaults.plugins.tooltip.bodyColor = T.dark ? '#c3cede' : 'rgba(255,255,255,.82)';
    Chart.defaults.plugins.tooltip.borderColor = T.dark ? 'rgba(255,255,255,.09)' : 'rgba(255,255,255,.08)';
    Chart.defaults.plugins.tooltip.borderWidth = 1;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.boxPadding = 5;
    Chart.defaults.plugins.tooltip.usePointStyle = true;
    Chart.defaults.plugins.tooltip.titleFont = { family: T.font, size: 11.5, weight: 650 };
    Chart.defaults.plugins.tooltip.bodyFont = { family: T.font, size: 11.5, weight: 500 };
    Chart.defaults.elements.point.radius = 0;
    Chart.defaults.elements.point.hoverRadius = 5;
    Chart.defaults.elements.point.hoverBorderWidth = 2;
    Chart.defaults.elements.point.hoverBackgroundColor = T.surface;
    Chart.defaults.elements.line.tension = 0.34;
    Chart.defaults.elements.line.borderWidth = 2.4;
    Chart.defaults.elements.bar.borderRadius = 5;
    Chart.defaults.elements.bar.borderSkipped = false;
    Chart.defaults.elements.arc.borderWidth = 2;
    Chart.defaults.elements.arc.borderColor = T.surface;
  }

  /* =======================================================================
   * 3. Shared axis / option factories
   * ===================================================================== */

  function gridX(T, opts) {
    return Object.assign(
      {
        grid: { display: false, drawBorder: false },
        border: { display: false },
        ticks: { color: T.tertiary, padding: 6, maxRotation: 0, autoSkipPadding: 14 },
      },
      opts || {}
    );
  }

  function gridY(T, opts) {
    return Object.assign(
      {
        grid: { color: T.dark ? 'rgba(255,255,255,.055)' : 'rgba(16,24,40,.055)', drawTicks: false, drawBorder: false },
        border: { display: false, dash: [4, 4] },
        ticks: { color: T.tertiary, padding: 10, maxTicksLimit: 6 },
        beginAtZero: true,
      },
      opts || {}
    );
  }

  /** Compact axis labels: 320000 → "$320K". */
  function moneyTick(T) {
    return {
      color: T.tertiary,
      padding: 10,
      maxTicksLimit: 6,
      callback: function (value) {
        var n = Number(value);
        if (Math.abs(n) >= 1e6) return '$' + (n / 1e6).toFixed(n % 1e6 === 0 ? 0 : 1) + 'M';
        if (Math.abs(n) >= 1e3) return '$' + Math.round(n / 1e3) + 'K';
        return '$' + n;
      },
    };
  }

  function legendRow(items) {
    return (
      '<div class="chart-legend">' +
      items
        .map(function (i) {
          return (
            '<span class="chart-legend-item">' +
            '<span class="chart-legend-swatch" style="background:' +
            i.color +
            '"></span>' +
            i.label +
            (i.value != null ? ' <span class="chart-legend-value">' + i.value + '</span>' : '') +
            '</span>'
          );
        })
        .join('') +
      '</div>'
    );
  }

  /* =======================================================================
   * 4. Formatters used in tooltips
   * ===================================================================== */

  var F = {
    money: function (v) {
      var n = Number(v) || 0;
      return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    money0: function (v) {
      return '$' + Math.round(Number(v) || 0).toLocaleString('en-US');
    },
    int: function (v) {
      return Math.round(Number(v) || 0).toLocaleString('en-US');
    },
    pct: function (v) {
      return (Number(v) || 0).toFixed(1) + '%';
    },
  };

  /* =======================================================================
   * 5. Range switcher state
   * The dashboard/analytics pages render a `.segmented` control whose buttons
   * carry [data-range="30d"]. Charts that depend on it re-read this value.
   * ===================================================================== */

  var RANGE = '30d';

  function currentRange() {
    var active = document.querySelector('[data-range].is-active');
    if (active) RANGE = active.getAttribute('data-range');
    return RANGE;
  }

  function rangeData(DB) {
    var key = currentRange();
    var ranges = DB.analytics.ranges;
    return ranges[key] || ranges['30d'];
  }

  /* =======================================================================
   * 6. Chart specs
   * Each entry: (DB, T) => { type, data, options, legend? }
   * `legend` returns HTML injected into [data-chart-legend="NAME"].
   * ===================================================================== */

  var SPECS = {};

  /* -- Dashboard: revenue + orders over the selected range --------------- */
  SPECS.revenueTrend = function (DB, T) {
    var r = rangeData(DB);
    return {
      type: 'line',
      data: {
        labels: r.labels,
        datasets: [
          {
            label: 'Revenue',
            data: r.revenue,
            borderColor: T.primary,
            backgroundColor: function (ctx) {
              return areaGradient(ctx, T.primaryRgb, 0.3, 0.01);
            },
            fill: true,
            yAxisID: 'y',
            pointHoverBorderColor: T.primary,
          },
          {
            label: 'Orders',
            data: r.orders,
            borderColor: T.info,
            backgroundColor: 'transparent',
            borderWidth: 1.8,
            borderDash: [5, 4],
            fill: false,
            yAxisID: 'y1',
            pointHoverBorderColor: T.info,
          },
        ],
      },
      options: {
        interaction: { mode: 'index', intersect: false },
        scales: {
          x: gridX(T),
          y: gridY(T, { position: 'left', ticks: moneyTick(T) }),
          y1: {
            position: 'right',
            grid: { display: false, drawBorder: false },
            border: { display: false },
            beginAtZero: true,
            ticks: { color: T.tertiary, padding: 8, maxTicksLimit: 5, callback: F.int },
          },
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function (c) {
                return ' ' + c.dataset.label + ': ' + (c.datasetIndex === 0 ? F.money0(c.parsed.y) : F.int(c.parsed.y));
              },
            },
          },
        },
      },
      legend: function () {
        return legendRow([
          { color: T.primary, label: 'Revenue', value: F.money0(r.revenue.reduce(function (a, b) { return a + b; }, 0)) },
          { color: T.info, label: 'Orders', value: F.int(r.orders.reduce(function (a, b) { return a + b; }, 0)) },
        ]);
      },
    };
  };

  /* -- Dashboard: revenue vs profit bars --------------------------------- */
  SPECS.revenueVsProfit = function (DB, T) {
    var r = rangeData(DB);
    return {
      type: 'bar',
      data: {
        labels: r.labels,
        datasets: [
          { label: 'Revenue', data: r.revenue, backgroundColor: rgba(T.primaryRgb, 0.85), maxBarThickness: 18 },
          { label: 'Profit', data: r.profit, backgroundColor: rgba(T.successRgb, 0.8), maxBarThickness: 18 },
        ],
      },
      options: {
        interaction: { mode: 'index', intersect: false },
        scales: { x: gridX(T), y: gridY(T, { ticks: moneyTick(T) }) },
        plugins: { tooltip: { callbacks: { label: function (c) { return ' ' + c.dataset.label + ': ' + F.money0(c.parsed.y); } } } },
      },
    };
  };

  /* -- Category revenue share (doughnut) --------------------------------- */
  SPECS.categoryShare = function (DB, T) {
    var rows = DB.analytics.salesByCategory.slice(0, 8);
    var colors = rows.map(function (row, i) {
      return row.color || T.series[i % T.series.length];
    });
    var total = rows.reduce(function (s, r) { return s + r.revenue; }, 0);
    return {
      type: 'doughnut',
      data: {
        labels: rows.map(function (r) { return r.name; }),
        datasets: [{ data: rows.map(function (r) { return r.revenue; }), backgroundColor: colors, hoverOffset: 6 }],
      },
      options: {
        cutout: '68%',
        plugins: {
          tooltip: {
            callbacks: {
              label: function (c) {
                return ' ' + c.label + ': ' + F.money0(c.parsed) + ' (' + ((c.parsed / total) * 100).toFixed(1) + '%)';
              },
            },
          },
        },
      },
      center: { value: F.money0(total), label: 'Total revenue' },
      legend: function () {
        return legendRow(
          rows.map(function (r, i) {
            return {
              color: colors[i],
              label: r.name,
              value: ((r.revenue / total) * 100).toFixed(1) + '%',
            };
          })
        );
      },
    };
  };

  /* -- Device split ------------------------------------------------------ */
  SPECS.deviceShare = function (DB, T) {
    var rows = DB.analytics.salesByDevice;
    var colors = [T.primary, T.info, T.warning];
    return {
      type: 'doughnut',
      data: {
        labels: rows.map(function (r) { return r.device; }),
        datasets: [{ data: rows.map(function (r) { return r.sessions; }), backgroundColor: colors, hoverOffset: 6 }],
      },
      options: {
        cutout: '64%',
        plugins: {
          tooltip: {
            callbacks: {
              label: function (c) {
                var row = rows[c.dataIndex];
                return ' ' + c.label + ': ' + F.int(row.sessions) + ' sessions · ' + row.share + '% · ' + F.pct(row.conversion) + ' conv.';
              },
            },
          },
        },
      },
      center: { value: F.int(rows.reduce(function (s, r) { return s + r.sessions; }, 0)), label: 'Sessions' },
      legend: function () {
        return legendRow(
          rows.map(function (r, i) {
            return { color: colors[i], label: r.device, value: r.share + '%' };
          })
        );
      },
    };
  };

  /* -- Traffic sources (horizontal bar) ---------------------------------- */
  SPECS.trafficSources = function (DB, T) {
    var rows = DB.analytics.trafficSources;
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.source; }),
        datasets: [
          {
            label: 'Sessions',
            data: rows.map(function (r) { return r.sessions; }),
            backgroundColor: rows.map(function (r, i) { return rgba(T.primaryRgb, 0.85 - i * 0.1); }),
            maxBarThickness: 18,
          },
        ],
      },
      options: {
        indexAxis: 'y',
        scales: {
          x: gridY(T, { ticks: { color: T.tertiary, padding: 6, maxTicksLimit: 5, callback: F.int } }),
          y: gridX(T, { ticks: { color: T.muted, padding: 8, font: { size: 11, weight: 550 } } }),
        },
        plugins: {
          tooltip: {
            callbacks: {
              afterLabel: function (c) {
                var row = rows[c.dataIndex];
                return 'Conversion ' + F.pct(row.conversion) + '\nRevenue ' + F.money0(row.revenue);
              },
            },
          },
        },
      },
    };
  };

  /* -- Revenue by country ------------------------------------------------ */
  SPECS.countryRevenue = function (DB, T) {
    var rows = DB.analytics.salesByCountry.slice(0, 8);
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.country; }),
        datasets: [{ label: 'Revenue', data: rows.map(function (r) { return r.revenue; }), backgroundColor: rgba(T.infoRgb, 0.82), maxBarThickness: 16 }],
      },
      options: {
        indexAxis: 'y',
        scales: {
          x: gridY(T, { ticks: moneyTick(T) }),
          y: gridX(T, { ticks: { color: T.muted, padding: 8, font: { size: 11, weight: 550 } } }),
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function (c) { return ' Revenue: ' + F.money0(c.parsed.x); },
              afterLabel: function (c) {
                var row = rows[c.dataIndex];
                return F.int(row.orders) + ' orders · ' + row.share + '% share · ' + (row.growth >= 0 ? '+' : '') + F.pct(row.growth);
              },
            },
          },
        },
      },
    };
  };

  /* -- Conversion funnel ------------------------------------------------- */
  SPECS.conversionFunnel = function (DB, T) {
    var rows = DB.analytics.conversionFunnel;
    var shades = [0.95, 0.8, 0.65, 0.5, 0.38];
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.stage; }),
        datasets: [{ label: 'Users', data: rows.map(function (r) { return r.value; }), backgroundColor: shades.map(function (a) { return rgba(T.primaryRgb, a); }), maxBarThickness: 26 }],
      },
      options: {
        indexAxis: 'y',
        scales: {
          x: gridY(T, { ticks: { color: T.tertiary, padding: 6, maxTicksLimit: 5, callback: F.int } }),
          y: gridX(T, { ticks: { color: T.muted, padding: 8, font: { size: 11, weight: 550 } } }),
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function (c) { return ' ' + F.int(c.parsed.x) + ' users'; },
              afterLabel: function (c) {
                var row = rows[c.dataIndex];
                var bits = [];
                if (row.pctOfTop != null) bits.push(F.pct(row.pctOfTop) + ' of sessions');
                if (row.dropoff != null) bits.push('−' + F.pct(row.dropoff) + ' drop-off');
                return bits.join('\n');
              },
            },
          },
        },
      },
    };
  };

  /* -- Actual vs target (bar + line) ------------------------------------- */
  SPECS.revenueVsTarget = function (DB, T) {
    var rows = DB.analytics.revenueVsTarget;
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.month; }),
        datasets: [
          { type: 'bar', label: 'Actual', data: rows.map(function (r) { return r.actual; }), backgroundColor: rgba(T.primaryRgb, 0.85), maxBarThickness: 22, order: 2 },
          { type: 'line', label: 'Target', data: rows.map(function (r) { return r.target; }), borderColor: T.warning, backgroundColor: 'transparent', borderWidth: 2, borderDash: [6, 4], pointRadius: 0, order: 1 },
        ],
      },
      options: {
        interaction: { mode: 'index', intersect: false },
        scales: { x: gridX(T), y: gridY(T, { ticks: moneyTick(T) }) },
        plugins: { tooltip: { callbacks: { label: function (c) { return ' ' + c.dataset.label + ': ' + F.money0(c.parsed.y); } } } },
      },
      legend: function () {
        return legendRow([
          { color: T.primary, label: 'Actual' },
          { color: T.warning, label: 'Target' },
        ]);
      },
    };
  };

  /* -- New vs returning customers (stacked) ------------------------------ */
  SPECS.newVsReturning = function (DB, T) {
    var rows = DB.reports.customers.newVsReturning;
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.month; }),
        datasets: [
          { label: 'New', data: rows.map(function (r) { return r.new; }), backgroundColor: rgba(T.primaryRgb, 0.88), stack: 'c', maxBarThickness: 24 },
          { label: 'Returning', data: rows.map(function (r) { return r.returning; }), backgroundColor: rgba(T.infoRgb, 0.7), stack: 'c', maxBarThickness: 24 },
        ],
      },
      options: {
        interaction: { mode: 'index', intersect: false },
        scales: {
          x: gridX(T, { stacked: true }),
          y: gridY(T, { stacked: true, ticks: { color: T.tertiary, padding: 10, maxTicksLimit: 6, callback: F.int } }),
        },
        plugins: { tooltip: { callbacks: { label: function (c) { return ' ' + c.dataset.label + ': ' + F.int(c.parsed.y); } } } },
      },
      legend: function () {
        return legendRow([
          { color: T.primary, label: 'New' },
          { color: T.info, label: 'Returning' },
        ]);
      },
    };
  };

  /* -- Cohort retention (bars + line) ------------------------------------ */
  SPECS.cohortRetention = function (DB, T) {
    var rows = DB.reports.customers.cohorts;
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.month; }),
        datasets: [
          { type: 'bar', label: 'Acquired', data: rows.map(function (r) { return r.acquired; }), backgroundColor: rgba(T.successRgb, 0.72), maxBarThickness: 20, yAxisID: 'y', order: 2 },
          { type: 'line', label: 'Retention', data: rows.map(function (r) { return r.retention; }), borderColor: T.primary, backgroundColor: 'transparent', yAxisID: 'y1', order: 1 },
        ],
      },
      options: {
        interaction: { mode: 'index', intersect: false },
        scales: {
          x: gridX(T),
          y: gridY(T, { ticks: { color: T.tertiary, padding: 10, maxTicksLimit: 6, callback: F.int } }),
          y1: {
            position: 'right', grid: { display: false }, border: { display: false }, beginAtZero: true, max: 100,
            ticks: { color: T.tertiary, padding: 8, maxTicksLimit: 5, callback: function (v) { return v + '%'; } },
          },
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function (c) {
                return ' ' + c.dataset.label + ': ' + (c.datasetIndex === 0 ? F.int(c.parsed.y) : F.pct(c.parsed.y));
              },
              afterLabel: function (c) {
                var row = rows[c.dataIndex];
                return 'LTV ' + F.money0(row.ltv) + '\nRevenue ' + F.money0(row.revenue);
              },
            },
          },
        },
      },
      legend: function () {
        return legendRow([
          { color: T.success, label: 'Acquired' },
          { color: T.primary, label: 'Retention %' },
        ]);
      },
    };
  };

  /* -- Customer segments (doughnut) -------------------------------------- */
  SPECS.customerSegments = function (DB, T) {
    var rows = DB.reports.customers.segments;
    var colors = rows.map(function (r, i) { return T.series[i % T.series.length]; });
    return {
      type: 'doughnut',
      data: {
        labels: rows.map(function (r) { return r.segment; }),
        datasets: [{ data: rows.map(function (r) { return r.customers; }), backgroundColor: colors, hoverOffset: 6 }],
      },
      options: {
        cutout: '66%',
        plugins: {
          tooltip: {
            callbacks: {
              label: function (c) {
                var row = rows[c.dataIndex];
                return ' ' + F.int(row.customers) + ' customers · ' + row.share + '%';
              },
              afterLabel: function (c) {
                var row = rows[c.dataIndex];
                return 'AOV ' + F.money(row.aov) + '\nLTV ' + F.money(row.ltv);
              },
            },
          },
        },
      },
      legend: function () {
        return legendRow(
          rows.map(function (r, i) {
            return { color: colors[i], label: r.segment, value: F.int(r.customers) };
          })
        );
      },
    };
  };

  /* -- Sales report: 12-month revenue / profit --------------------------- */
  SPECS.salesTrend = function (DB, T) {
    var rows = DB.reports.sales;
    return {
      type: 'line',
      data: {
        labels: rows.map(function (r) { return r.month; }),
        datasets: [
          {
            label: 'Revenue', data: rows.map(function (r) { return r.revenue; }), borderColor: T.primary,
            backgroundColor: function (ctx) { return areaGradient(ctx, T.primaryRgb, 0.26, 0.01); },
            fill: true,
          },
          {
            label: 'Profit', data: rows.map(function (r) { return r.profit; }), borderColor: T.success,
            backgroundColor: function (ctx) { return areaGradient(ctx, T.successRgb, 0.16, 0.01); },
            fill: true,
          },
        ],
      },
      options: {
        interaction: { mode: 'index', intersect: false },
        scales: { x: gridX(T), y: gridY(T, { ticks: moneyTick(T) }) },
        plugins: {
          tooltip: {
            callbacks: {
              label: function (c) { return ' ' + c.dataset.label + ': ' + F.money0(c.parsed.y); },
              afterBody: function (items) {
                var row = rows[items[0].dataIndex];
                return ['Orders ' + F.int(row.orders), 'AOV ' + F.money(row.aov), 'Margin ' + F.pct(row.margin)];
              },
            },
          },
        },
      },
      legend: function () {
        return legendRow([
          { color: T.primary, label: 'Revenue' },
          { color: T.success, label: 'Profit' },
        ]);
      },
    };
  };

  /* -- Sales report: cost breakdown (stacked) ---------------------------- */
  SPECS.costBreakdown = function (DB, T) {
    var rows = DB.reports.sales;
    var parts = [
      { key: 'cogs', label: 'COGS', color: T.danger },
      { key: 'discounts', label: 'Discounts', color: T.warning },
      { key: 'shipping', label: 'Shipping', color: T.info },
      { key: 'taxes', label: 'Taxes', color: T.muted },
      { key: 'refunds', label: 'Refunds', color: T.tertiary },
    ];
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.month; }),
        datasets: parts.map(function (p) {
          return {
            label: p.label,
            data: rows.map(function (r) { return r[p.key]; }),
            backgroundColor: p.color,
            stack: 'cost',
            maxBarThickness: 22,
          };
        }),
      },
      options: {
        interaction: { mode: 'index', intersect: false },
        scales: {
          x: gridX(T, { stacked: true }),
          y: gridY(T, { stacked: true, ticks: moneyTick(T) }),
        },
        plugins: { tooltip: { callbacks: { label: function (c) { return ' ' + c.dataset.label + ': ' + F.money0(c.parsed.y); } } } },
      },
      legend: function () {
        return legendRow(parts.map(function (p) { return { color: p.color, label: p.label }; }));
      },
    };
  };

  /* -- Products report: top sellers by revenue --------------------------- */
  SPECS.topProductsRevenue = function (DB, T) {
    var rows = DB.analytics.topProducts.slice(0, 8);
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.name.length > 26 ? r.name.slice(0, 25) + '…' : r.name; }),
        datasets: [{ label: 'Revenue', data: rows.map(function (r) { return r.revenue; }), backgroundColor: rgba(T.primaryRgb, 0.85), maxBarThickness: 16 }],
      },
      options: {
        indexAxis: 'y',
        scales: {
          x: gridY(T, { ticks: moneyTick(T) }),
          y: gridX(T, { ticks: { color: T.muted, padding: 8, font: { size: 10.5, weight: 550 }, autoSkip: false } }),
        },
        plugins: {
          tooltip: {
            callbacks: {
              title: function (items) { return rows[items[0].dataIndex].name; },
              label: function (c) { return ' Revenue: ' + F.money0(c.parsed.x); },
              afterLabel: function (c) {
                var r = rows[c.dataIndex];
                return F.int(r.sold) + ' units · ' + r.brandName + '\nProfit ' + F.money0(r.profit) + ' · conv. ' + F.pct(r.conversion);
              },
            },
          },
        },
      },
    };
  };

  /* -- Products report: margin leaders ----------------------------------- */
  SPECS.margins = function (DB, T) {
    var rows = DB.reports.products.margins.slice(0, 10);
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.sku; }),
        datasets: [
          { label: 'Margin %', data: rows.map(function (r) { return r.margin; }), backgroundColor: rows.map(function (r) { return r.margin >= 30 ? rgba(T.successRgb, 0.85) : r.margin >= 18 ? rgba(T.warningRgb, 0.85) : rgba(T.dangerRgb, 0.8); }), maxBarThickness: 20 },
        ],
      },
      options: {
        scales: {
          x: gridX(T, { ticks: { color: T.tertiary, padding: 6, font: { size: 9.5 }, maxRotation: 60, minRotation: 45 } }),
          y: gridY(T, { max: 60, ticks: { color: T.tertiary, padding: 10, maxTicksLimit: 6, callback: function (v) { return v + '%'; } } }),
        },
        plugins: {
          tooltip: {
            callbacks: {
              title: function (items) { return rows[items[0].dataIndex].name; },
              label: function (c) {
                var r = rows[c.dataIndex];
                return ' Margin: ' + F.pct(r.margin) + ' (' + F.money(r.price - r.cost) + '/unit)';
              },
              afterLabel: function (c) {
                var r = rows[c.dataIndex];
                return 'Price ' + F.money(r.price) + ' · cost ' + F.money(r.cost) + '\nProfit ' + F.money0(r.profit);
              },
            },
          },
        },
      },
    };
  };

  /* -- Inventory: stock health doughnut ---------------------------------- */
  SPECS.stockHealth = function (DB, T) {
    var h = DB.reports.products.stockHealth;
    var rows = [
      { label: 'In stock', value: h.inStock, color: T.success },
      { label: 'Medium', value: h.medium, color: T.info },
      { label: 'Low', value: h.low, color: T.warning },
      { label: 'Out of stock', value: h.out, color: T.danger },
    ];
    return {
      type: 'doughnut',
      data: {
        labels: rows.map(function (r) { return r.label; }),
        datasets: [{ data: rows.map(function (r) { return r.value; }), backgroundColor: rows.map(function (r) { return r.color; }), hoverOffset: 6 }],
      },
      options: {
        cutout: '66%',
        plugins: { tooltip: { callbacks: { label: function (c) { return ' ' + c.label + ': ' + F.int(c.parsed) + ' SKUs'; } } } },
      },
      center: { value: F.int(h.inStock + h.medium + h.low + h.out), label: 'Tracked SKUs' },
      legend: function () {
        return legendRow(rows.map(function (r) { return { color: r.color, label: r.label, value: F.int(r.value) }; }));
      },
    };
  };

  /* -- Inventory: units by category -------------------------------------- */
  SPECS.stockByCategory = function (DB, T) {
    var totals = {};
    DB.inventory.forEach(function (row) {
      var name = row.categoryName || 'Other';
      totals[name] = (totals[name] || 0) + (row.stock || 0);
    });
    var rows = Object.keys(totals)
      .map(function (name) { return { name: name, stock: totals[name] }; })
      .sort(function (a, b) { return b.stock - a.stock; })
      .slice(0, 10);
    return {
      type: 'bar',
      data: {
        labels: rows.map(function (r) { return r.name; }),
        datasets: [{ label: 'Units on hand', data: rows.map(function (r) { return r.stock; }), backgroundColor: rgba(T.infoRgb, 0.8), maxBarThickness: 22 }],
      },
      options: {
        scales: {
          x: gridX(T, { ticks: { color: T.tertiary, padding: 6, font: { size: 9.5 }, maxRotation: 55, minRotation: 40 } }),
          y: gridY(T, { ticks: { color: T.tertiary, padding: 10, maxTicksLimit: 6, callback: F.int } }),
        },
        plugins: { tooltip: { callbacks: { label: function (c) { return ' ' + F.int(c.parsed.y) + ' units on hand'; } } } },
      },
    };
  };

  /* -- Orders: status split ---------------------------------------------- */
  SPECS.ordersByStatus = function (DB, T) {
    var counts = {};
    DB.orders.forEach(function (o) { counts[o.status] = (counts[o.status] || 0) + 1; });
    var palette = { pending: T.warning, processing: T.info, shipped: T.primary, delivered: T.success, cancelled: T.danger, refunded: T.tertiary };
    var keys = Object.keys(counts).sort(function (a, b) { return counts[b] - counts[a]; });
    return {
      type: 'doughnut',
      data: {
        labels: keys.map(function (k) { return k.charAt(0).toUpperCase() + k.slice(1); }),
        datasets: [{ data: keys.map(function (k) { return counts[k]; }), backgroundColor: keys.map(function (k) { return palette[k] || T.muted; }), hoverOffset: 6 }],
      },
      options: {
        cutout: '64%',
        plugins: { tooltip: { callbacks: { label: function (c) { return ' ' + c.label + ': ' + c.parsed + ' orders'; } } } },
      },
      center: { value: F.int(DB.orders.length), label: 'Sample orders' },
      legend: function () {
        return legendRow(keys.map(function (k) { return { color: palette[k] || T.muted, label: k, value: F.int(counts[k]) }; }));
      },
    };
  };

  /* -- Payments: method split -------------------------------------------- */
  SPECS.paymentMethods = function (DB, T) {
    var counts = {};
    var totals = {};
    DB.payments.forEach(function (p) {
      var m = p.method || p.paymentMethod || 'Other';
      counts[m] = (counts[m] || 0) + 1;
      totals[m] = (totals[m] || 0) + (p.amount || 0);
    });
    var keys = Object.keys(counts).sort(function (a, b) { return counts[b] - counts[a]; });
    return {
      type: 'doughnut',
      data: {
        labels: keys,
        datasets: [{ data: keys.map(function (k) { return totals[k]; }), backgroundColor: keys.map(function (k, i) { return T.series[i % T.series.length]; }), hoverOffset: 6 }],
      },
      options: {
        cutout: '64%',
        plugins: {
          tooltip: {
            callbacks: {
              label: function (c) { return ' ' + F.money0(c.parsed) + ' captured'; },
              afterLabel: function (c) { return F.int(counts[c.label]) + ' transactions'; },
            },
          },
        },
      },
      center: { value: F.money0(keys.reduce(function (s, k) { return s + totals[k]; }, 0)), label: 'Captured' },
      legend: function () {
        return legendRow(keys.map(function (k, i) { return { color: T.series[i % T.series.length], label: k, value: F.int(counts[k]) }; }));
      },
    };
  };

  /* -- Customers: growth over time --------------------------------------- */
  SPECS.customerGrowth = function (DB, T) {
    var rows = DB.reports.customers.newVsReturning;
    var running = 0;
    var cumulative = rows.map(function (r) { running += r.new; return running; });
    return {
      type: 'line',
      data: {
        labels: rows.map(function (r) { return r.month; }),
        datasets: [
          { label: 'New customers', data: rows.map(function (r) { return r.new; }), borderColor: T.info, backgroundColor: 'transparent', yAxisID: 'y' },
          {
            label: 'Cumulative', data: cumulative, borderColor: T.primary,
            backgroundColor: function (ctx) { return areaGradient(ctx, T.primaryRgb, 0.24, 0.01); },
            fill: true, yAxisID: 'y1',
          },
        ],
      },
      options: {
        interaction: { mode: 'index', intersect: false },
        scales: {
          x: gridX(T),
          y: gridY(T, { ticks: { color: T.tertiary, padding: 10, maxTicksLimit: 6, callback: F.int } }),
          y1: { position: 'right', grid: { display: false }, border: { display: false }, beginAtZero: true, ticks: { color: T.tertiary, padding: 8, maxTicksLimit: 5, callback: F.int } },
        },
        plugins: { tooltip: { callbacks: { label: function (c) { return ' ' + c.dataset.label + ': ' + F.int(c.parsed.y); } } } },
      },
      legend: function () {
        return legendRow([
          { color: T.info, label: 'New' },
          { color: T.primary, label: 'Cumulative' },
        ]);
      },
    };
  };

  /* -- Analytics: orders per hour (averaged across the week) ------------- */
  SPECS.hourlyOrders = function (DB, T) {
    var matrix = DB.analytics.hourlyByWeekday;
    var hours = Array.from({ length: 24 }, function (_, h) {
      var sum = matrix.reduce(function (s, day) { return s + (day[h] || 0); }, 0);
      return Math.round(sum / matrix.length);
    });
    var labels = hours.map(function (_, h) {
      var suffix = h < 12 ? 'AM' : 'PM';
      var hour12 = h % 12 === 0 ? 12 : h % 12;
      return hour12 + suffix;
    });
    return {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{ label: 'Avg orders / hour', data: hours, backgroundColor: hours.map(function (v) { return rgba(T.primaryRgb, 0.35 + (v / Math.max.apply(null, hours)) * 0.55); }), maxBarThickness: 14 }],
      },
      options: {
        scales: {
          x: gridX(T, { ticks: { color: T.tertiary, padding: 6, font: { size: 9 }, maxRotation: 0, autoSkip: false, callback: function (v, i) { return i % 2 === 0 ? this.getLabelForValue(v) : ''; } } }),
          y: gridY(T, { ticks: { color: T.tertiary, padding: 10, maxTicksLimit: 5, callback: F.int } }),
        },
        plugins: { tooltip: { callbacks: { label: function (c) { return ' ~' + F.int(c.parsed.y) + ' orders/hour'; } } } },
      },
    };
  };

  /* =======================================================================
   * 7. Heatmap (no Chart.js equivalent — pure DOM)
   * Renders analytics.hourlyByWeekday into [data-heatmap].
   * ===================================================================== */

  var DAY_LABELS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

  function buildHeatmap(el, DB) {
    var matrix = DB.analytics.hourlyByWeekday;
    if (!matrix || !matrix.length) return;

    var max = 0;
    matrix.forEach(function (day) {
      day.forEach(function (v) { if (v > max) max = v; });
    });

    var head = '<div class="heatmap-corner"></div>';
    for (var h = 0; h < 24; h++) {
      var hour12 = h % 12 === 0 ? 12 : h % 12;
      head += '<div class="heatmap-col-label">' + (h % 2 === 0 ? hour12 + (h < 12 ? 'a' : 'p') : '') + '</div>';
    }

    var body = '';
    matrix.forEach(function (day, d) {
      body += '<div class="heatmap-row-label">' + DAY_LABELS[d % 7] + '</div>';
      for (var hh = 0; hh < 24; hh++) {
        var value = day[hh] || 0;
        var intensity = max ? value / max : 0;
        body +=
          '<div class="heatmap-cell" data-level="' +
          (intensity > 0.75 ? 4 : intensity > 0.5 ? 3 : intensity > 0.25 ? 2 : intensity > 0 ? 1 : 0) +
          '" style="--heat:' +
          intensity.toFixed(3) +
          '" data-bs-toggle="tooltip" title="' +
          DAY_LABELS[d % 7] +
          ' ' +
          hh +
          ':00 — ' +
          value +
          ' orders"></div>';
      }
    });

    el.innerHTML =
      '<div class="heatmap-grid" style="--heatmap-cols:24">' + head + body + '</div>' +
      '<div class="heatmap-scale"><span>Less</span>' +
      [0, 1, 2, 3, 4].map(function (l) { return '<i data-level="' + l + '"></i>'; }).join('') +
      '<span>More</span></div>';

    if (window.AdminUI) window.AdminUI.tooltips(el);
  }

  /* =======================================================================
   * 8. Mount / refresh lifecycle
   * ===================================================================== */

  var live = []; // { canvas, name, chart }

  function buildOne(canvas, T, DB) {
    var name = canvas.getAttribute('data-chart');
    var spec = SPECS[name];
    if (!spec) {
      if (window.console) console.warn('[charts] no spec named "' + name + '"');
      return null;
    }

    var config = spec(DB, T);
    if (!config) return null;

    var chart = new Chart(canvas.getContext('2d'), {
      type: config.type,
      data: config.data,
      options: config.options || {},
    });

    var box = canvas.closest('.chart-box') || canvas.parentElement;

    // Optional centred label for doughnuts.
    if (config.center && box) {
      var old = box.querySelector('.donut-center');
      if (old) old.remove();
      var center = document.createElement('div');
      center.className = 'donut-center';
      center.innerHTML =
        '<span class="donut-center-value">' + config.center.value + '</span>' +
        '<span class="donut-center-label">' + config.center.label + '</span>';
      box.appendChild(center);
    }

    // Optional external legend.
    if (config.legend) {
      var slot = document.querySelector('[data-chart-legend="' + name + '"]');
      if (slot) slot.innerHTML = config.legend();
    }

    return { canvas: canvas, name: name, chart: chart, spec: spec };
  }

  function mountAll() {
    var DB = window.DB;
    if (!DB) return;
    var T = tokens();
    applyDefaults(T);

    document.querySelectorAll('canvas[data-chart]').forEach(function (canvas) {
      // Skip charts inside a tab/pane that is display:none — Chart.js sizes
      // them to zero. They mount on first show instead.
      if (canvas.offsetParent === null && !canvas.closest('.tab-pane')) return;
      if (canvas.__ncChart) return;
      var entry = buildOne(canvas, T, DB);
      if (entry) {
        canvas.__ncChart = entry.chart;
        live.push(entry);
      }
    });

    document.querySelectorAll('[data-heatmap]').forEach(function (el) {
      if (!el.__ncHeat) {
        buildHeatmap(el, DB);
        el.__ncHeat = true;
      }
    });
  }

  /** Full rebuild — the only reliable way to restyle gradients + arc borders. */
  function rebuild() {
    live.forEach(function (entry) {
      entry.chart.destroy();
      delete entry.canvas.__ncChart;
    });
    live = [];
    var T = tokens();
    applyDefaults(T);
    var DB = window.DB;
    if (!DB) return;
    document.querySelectorAll('canvas[data-chart]').forEach(function (canvas) {
      if (canvas.offsetParent === null && !canvas.closest('.tab-pane')) return;
      var entry = buildOne(canvas, T, DB);
      if (entry) {
        canvas.__ncChart = entry.chart;
        live.push(entry);
      }
    });
  }

  /** Re-read the range control and rebuild only the range-sensitive charts. */
  function refreshRange() {
    var DB = window.DB;
    if (!DB) return;
    var T = tokens();
    applyDefaults(T);
    live.forEach(function (entry) {
      if (!/revenueTrend|revenueVsProfit/.test(entry.name)) return;
      var config = entry.spec(DB, T);
      entry.chart.data = config.data;
      entry.chart.options = Object.assign({}, entry.chart.options, config.options);
      entry.chart.update();
      if (config.legend) {
        var slot = document.querySelector('[data-chart-legend="' + entry.name + '"]');
        if (slot) slot.innerHTML = config.legend();
      }
    });
  }

  window.AdminCharts = {
    specs: SPECS,
    tokens: tokens,
    defaults: applyDefaults,
    mount: mountAll,
    rebuild: rebuild,
    refreshRange: refreshRange,
    heatmap: buildHeatmap,
    range: currentRange,
    setRange: function (key) {
      RANGE = key;
    },
    fmt: F,
    palette: CATEGORICAL,
    rgba: rgba,
    gradient: areaGradient,
    axis: { x: gridX, y: gridY, money: moneyTick },
    legendRow: legendRow,
    get: function (name) {
      for (var i = 0; i < live.length; i++) if (live[i].name === name) return live[i].chart;
      return null;
    },
  };

  /* -- wiring ------------------------------------------------------------ */

  function onReady(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  onReady(function () {
    mountAll();

    // Charts inside tabs mount the first time their pane is shown.
    document.addEventListener('shown.bs.tab', function (event) {
      var target = event.target.getAttribute('data-bs-target');
      if (!target) return;
      var pane = document.querySelector(target);
      if (!pane) return;
      var T = tokens();
      applyDefaults(T);
      pane.querySelectorAll('canvas[data-chart]').forEach(function (canvas) {
        if (canvas.__ncChart) {
          canvas.__ncChart.resize();
          return;
        }
        var entry = buildOne(canvas, T, window.DB);
        if (entry) {
          canvas.__ncChart = entry.chart;
          live.push(entry);
        }
      });
    });

    // Any [data-range] control drives the range-sensitive charts.
    document.addEventListener('click', function (event) {
      var btn = event.target.closest ? event.target.closest('[data-range]') : null;
      if (!btn) return;
      var group = btn.closest('.segmented, .btn-group, [data-range-group]');
      if (group) {
        group.querySelectorAll('[data-range]').forEach(function (b) { b.classList.remove('is-active'); });
      }
      btn.classList.add('is-active');
      var label = document.querySelector('[data-range-label]');
      if (label) label.textContent = btn.getAttribute('data-range-label') || btn.textContent.trim();
      RANGE = btn.getAttribute('data-range');
      refreshRange();
      document.dispatchEvent(new CustomEvent('nc:rangechange', { detail: { range: RANGE } }));
    });
  });

  window.addEventListener('nc:themechange', function () {
    // Let the CSS variables settle before re-reading them.
    window.setTimeout(rebuild, 30);
  });
})(window, document);
