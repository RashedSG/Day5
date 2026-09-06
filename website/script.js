(function () {
  var nav = document.getElementById("site-nav");
  var btn = document.querySelector(".nav-toggle");
  if (btn && nav) {
    btn.addEventListener("click", function () {
      var open = nav.classList.toggle("is-open");
      btn.setAttribute("aria-expanded", open ? "true" : "false");
    });
  }
  document.querySelectorAll("[data-year]").forEach(function (el) {
    el.textContent = String(new Date().getFullYear());
  });
  var chips = document.getElementById("chips");
  var cards = document.querySelectorAll("#service-grid .card");
  if (chips && cards.length) {
    chips.addEventListener("click", function (e) {
      var b = e.target.closest(".chip");
      if (!b) return;
      var cat = b.getAttribute("data-cat");
      chips.querySelectorAll(".chip").forEach(function (c) {
        c.setAttribute("aria-pressed", c === b ? "true" : "false");
      });
      cards.forEach(function (card) {
        card.hidden = cat !== "all" && card.getAttribute("data-cat") !== cat;
      });
    });
  }
  var search = document.getElementById("q");
  if (search && cards.length) {
    search.addEventListener("input", function () {
      var t = search.value.trim();
      cards.forEach(function (card) {
        card.hidden = t !== "" && card.textContent.indexOf(t) === -1;
      });
    });
  }
  document.querySelectorAll("form.js-form").forEach(function (form) {
    var status = form.querySelector(".form-status");
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var bad = false;
      ["name", "email", "message"].forEach(function (n) {
        var f = form.elements[n];
        var box = form.querySelector("#err-" + n);
        if (!f) return;
        var msg = "";
        var v = (f.value || "").trim();
        if (!v) msg = "هذا الحقل مطلوب.";
        else if (n === "email" && !/[^\s@]+@[^\s@]+\.[^\s@]+/.test(v)) msg = "البريد غير صحيح.";
        else if (n === "message" && v.length < 8) msg = "أضف تفاصيل أكثر.";
        if (box) box.textContent = msg;
        if (msg) { f.setAttribute("aria-invalid", "true"); bad = true; }
        else f.removeAttribute("aria-invalid");
      });
      if (bad) {
        if (status) { status.className = "form-status is-error"; status.textContent = "راجع الحقول ثم أعد الإرسال."; }
        return;
      }
      try {
        var list = JSON.parse(localStorage.getItem("wasla-msgs") || "[]");
        list.unshift({ name: form.elements.name.value, at: new Date().toISOString() });
        localStorage.setItem("wasla-msgs", JSON.stringify(list.slice(0, 20)));
      } catch (err) {}
      form.reset();
      if (status) { status.className = "form-status is-success"; status.textContent = "حفظنا رسالتك على هذا الجهاز. راسل hello@wasla.ae للتأكيد."; }
    });
  });
})();
