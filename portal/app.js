function $(sel, root) { return (root || document).querySelector(sel); }
async function loadJSON(path) {
  var res = await fetch(path, { cache: "no-store" });
  if (!res.ok) throw new Error("تعذّر تحميل " + path);
  return res.json();
}
function fillStats(el, items) {
  if (!el) return;
  el.innerHTML = items.map(function (s) {
    return "<li class=\"stat\"><b>" + s.n + "</b><span>" + s.label + "</span></li>";
  }).join("");
}
function renderApps(apps) {
  var box = $("#app-grid");
  if (!box) return;
  box.innerHTML = apps.map(function (a) {
    var href = a.page ? "<p><a href=\"" + a.page + "\">افتح الصفحة</a></p>" : "";
    var json = a.json ? "<p class=\"json-link\"><a href=\"" + a.json + "\">" + a.json + "</a></p>" : "<p class=\"muted\">لا يوجد JSON بعد</p>";
    return "<li class=\"card\"><p class=\"muted\">" + a.unit_label + "</p><h2>" + a.name + "</h2><p class=\"muted\">" + a.summary + "</p><p><span class=\"tag is-" + a.status + "\">" + (a.status === "active" ? "مفعّل" : "مخطط") + "</span> · " + a.owner + "</p>" + href + json + "</li>";
  }).join("");
}
function renderEmployees(rows) {
  var tb = $("#emp-body");
  if (!tb) return;
  tb.innerHTML = rows.map(function (e) {
    return "<tr><td>" + e.code + "</td><td>" + e.name + "</td><td>" + e.role + "</td><td>" + e.unit_label + "</td><td class=\"hide-sm\">" + e.email + "</td><td><span class=\"tag is-" + e.status + "\">نشط</span></td></tr>";
  }).join("");
}
function renderHardware(rows) {
  var tb = $("#hw-body");
  if (!tb) return;
  tb.innerHTML = rows.map(function (i) {
    return "<tr><td dir=\"ltr\">" + i.code + "</td><td>" + i.name + "</td><td>" + (i.category_label || i.category) + "</td><td><span class=\"tag is-" + i.status + "\">" + i.status_label + "</span></td><td>" + i.location + "</td><td class=\"hide-sm\">" + i.custodian + "</td></tr>";
  }).join("");
}
function bindFilter(input, apply) {
  if (!input) return;
  input.addEventListener("input", apply);
  input.addEventListener("change", apply);
}
document.addEventListener("DOMContentLoaded", async function () {
  var page = document.body.getAttribute("data-page");
  try {
    if (page === "apps") {
      var pack = await loadJSON("data/applications.json");
      var apps = pack.applications || [];
      fillStats($("#stats"), [
        { n: apps.length, label: "تطبيقات" },
        { n: apps.filter(function (a) { return a.status === "active"; }).length, label: "مفعّلة" },
        { n: (pack.units || []).length, label: "وحدات مسموحة" }
      ]);
      renderApps(apps);
      var unit = $("#unit");
      bindFilter(unit, function () {
        var v = unit.value;
        renderApps(v ? apps.filter(function (a) { return a.unit === v; }) : apps);
      });
    }
    if (page === "employees") {
      var emp = await loadJSON("data/employees.json");
      var rows = emp.employees || [];
      fillStats($("#stats"), [
        { n: rows.length, label: "موظفون" },
        { n: rows.filter(function (e) { return e.unit === "hardware"; }).length, label: "وحدة الأجهزة" }
      ]);
      renderEmployees(rows);
      var q = $("#q"), unit = $("#unit");
      function applyEmp() {
        var t = (q && q.value || "").trim();
        var u = unit && unit.value || "";
        renderEmployees(rows.filter(function (e) {
          return (!u || e.unit === u) && (!t || (e.name + e.role + e.code).indexOf(t) !== -1);
        }));
      }
      bindFilter(q, applyEmp);
      bindFilter(unit, applyEmp);
    }
    if (page === "hardware") {
      var hw = await loadJSON("data/hardware.json");
      var cats = {};
      (hw.categories || []).forEach(function (c) { cats[c.id] = c.label; });
      var items = (hw.items || []).map(function (i) {
        i.category_label = cats[i.category] || i.category;
        return i;
      });
      fillStats($("#stats"), [
        { n: items.length, label: "أصول" },
        { n: items.filter(function (i) { return i.status === "in_use"; }).length, label: "قيد الاستخدام" },
        { n: items.filter(function (i) { return i.status === "maintenance"; }).length, label: "صيانة" }
      ]);
      var sel = $("#category");
      if (sel) {
        (hw.categories || []).forEach(function (c) {
          var o = document.createElement("option");
          o.value = c.id; o.textContent = c.label; sel.appendChild(o);
        });
      }
      renderHardware(items);
      var qh = $("#q");
      function applyHw() {
        var t = (qh && qh.value || "").trim();
        var c = sel && sel.value || "";
        renderHardware(items.filter(function (i) {
          return (!c || i.category === c) && (!t || (i.name + i.code + i.location + i.custodian).indexOf(t) !== -1);
        }));
      }
      bindFilter(qh, applyHw);
      bindFilter(sel, applyHw);
    }
  } catch (err) {
    var box = $("#error");
    if (box) box.textContent = err.message;
  }
});
