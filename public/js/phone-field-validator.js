/**
 * PhoneFieldValidator
 * ────────────────────
 * Single, reusable wrapper around intl-tel-input (v25) for every phone
 * field in Fairy Marketing CRM — static fields, dynamically-added fields
 * ("+" button), and pre-populated fields on edit pages.
 *
 * WHY THIS EXISTS:
 * Every blade page used to have its own ~150-line copy of this logic,
 * copy-pasted with small drifts each time (different intl-tel-input
 * versions, mismatched CDN URLs, subtly different bugs). This file is now
 * the ONE place that logic lives. If intl-tel-input ever needs upgrading
 * again, or a bug needs fixing, it only needs to change here.
 *
 * USAGE — static field (e.g. the main "Mobile Phone" field):
 *   PhoneFieldValidator.init(document.querySelector('.phone'), {
 *       errorMsgEl: document.querySelector('#error-msg'),
 *       resultEl: document.querySelector('#result'),
 *       hiddenFieldName: 'full_number',
 *   });
 *
 * USAGE — dynamically-created field (inside a "+" button click handler):
 *   var iti = PhoneFieldValidator.init(phoneInput, {
 *       errorMsgEl: errorMsgDiv,
 *       resultEl: resultDiv,
 *       hiddenFieldName: 'international_full_number' + counter,
 *   });
 *
 * USAGE — pre-populated field on an edit page (input already has a value):
 *   Same call as above — the field stays plain (no checkmark/border/result
 *   text) until the user actually types, changes, or blurs it. Existing
 *   saved numbers are trusted as-is on load and don't get re-validated
 *   just because the page opened.
 *
 * Requires jQuery (for .addClass/.removeClass/.show/.hide, matching the
 * rest of the CRM's existing code) and the intl-tel-input v25 CSS/JS,
 * which are loaded once via partials/phone-validator-assets.blade.php.
 */
window.PhoneFieldValidator = (function () {
    var UTILS_URL =
        "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js";

    // v25's getValidationError() codes — stable across intl-tel-input
    // versions (these come from the underlying libphonenumber library).
    var ERROR_MAP = {
        0: "Invalid number",
        1: "Invalid country code",
        2: "Too short",
        3: "Too long",
        4: "Invalid number (missing country code)",
        5: "Invalid length for this country",
    };

    /**
     * @param {HTMLInputElement} inputEl - the visible phone text input
     * @param {Object} [options]
     * @param {HTMLElement} [options.errorMsgEl] - element to show the error text in (e.g. "Too short")
     * @param {HTMLElement} [options.resultEl] - element to show "Number: ..., Correct/Wrong"
     * @param {string} [options.hiddenFieldName] - name attribute for the auto-created hidden E.164 field (e.g. "full_number"). Omit to skip hidden-field creation.
     * @param {string} [options.initialCountry="pk"]
     * @param {string[]} [options.countryOrder=["il","ge"]]
     * @returns {Object} the intl-tel-input instance, in case the caller needs direct access
     */
    function init(inputEl, options) {
        options = options || {};
        var errorMsgEl = options.errorMsgEl || null;
        var resultEl = options.resultEl || null;
        var hiddenFieldName = options.hiddenFieldName || null;
        var initialCountry = options.initialCountry || "pk";
        var countryOrder = options.countryOrder || ["il", "ge"];

        var iti = window.intlTelInput(inputEl, {
            initialCountry: initialCountry,
            separateDialCode: true,
            numberDisplayFormat: "NATIONAL",
            strictMode: true, // blocks letters + caps length at the correct max for the selected country
            formatAsYouType: true,
            placeholderNumberPolicy: "AGGRESSIVE",
            countryOrder: countryOrder,
            loadUtils: function () {
                return import(UTILS_URL);
            },
        });

        // Extra safety net: strip any non-digit character (paste, etc.)
        inputEl.addEventListener("input", function () {
            var cleaned = inputEl.value.replace(/[^0-9]/g, "");
            if (cleaned !== inputEl.value) {
                inputEl.value = cleaned;
            }
        });

        // Hidden field carrying the full E.164 number for the backend.
        // Created manually here (not via intl-tel-input's own hiddenInput
        // option) so the exact field name is guaranteed regardless of
        // library version.
        var hiddenField = null;
        if (hiddenFieldName) {
            hiddenField = document.createElement("input");
            hiddenField.type = "hidden";
            hiddenField.name = hiddenFieldName;
            var form = inputEl.closest("form");
            if (form) {
                form.appendChild(hiddenField);
            }
        }

        function reset() {
            $(inputEl).removeClass("form-control is-invalid");
            if (errorMsgEl) {
                errorMsgEl.innerHTML = "";
                // Toggle the class directly, not just jQuery's .hide() —
                // some admin themes define ".hide { display: none !important; }",
                // which a plain inline-style .show()/.hide() can't override.
                errorMsgEl.classList.add("hide");
                $(errorMsgEl).hide();
            }
        }

        function updateHiddenField() {
            if (hiddenField) {
                hiddenField.value = iti.getNumber() || "";
            }
        }

        function validateAndDisplay() {
            reset();
            updateHiddenField();
            if (inputEl.value.trim()) {
                if (iti.isValidNumber()) {
                    $(inputEl).addClass("form-control is-valid");
                    if (resultEl)
                        resultEl.textContent =
                            "Number: " + iti.getNumber() + ", Correct ";
                } else {
                    $(inputEl).addClass("form-control is-invalid");
                    var errorCode = iti.getValidationError();
                    if (errorMsgEl) {
                        errorMsgEl.innerHTML =
                            ERROR_MAP[errorCode] || "Invalid number";
                        errorMsgEl.classList.remove("hide");
                        $(errorMsgEl).show();
                    }
                    if (resultEl)
                        resultEl.textContent =
                            "Number: " + iti.getNumber() + ", Wrong ";
                }
            } else if (resultEl) {
                resultEl.textContent = "";
            }
        }

        // Wait for utils.js before doing anything validation-related —
        // validating against a half-loaded library was the root cause of
        // an earlier bug (every number showed invalid).
        iti.promise.then(function () {
            inputEl.addEventListener("keyup", validateAndDisplay);
            inputEl.addEventListener("change", validateAndDisplay);

            // On losing focus: hide the "Correct" result text once valid,
            // but leave the "Wrong"/error message visible until the
            // number actually becomes valid.
            inputEl.addEventListener("blur", function () {
                if (iti.isValidNumber() && resultEl) {
                    resultEl.textContent = "";
                }
            });

            // // Edit-page case: the field may already have a value when the
            // // page loads (an existing saved number). Validate it once
            // // immediately so its correct/invalid state shows right away,
            // // without waiting for the user to type.
            // if (inputEl.value.trim()) {
            //     validateAndDisplay();
            // }
        });

        inputEl.addEventListener("focus", function () {
            if (resultEl) resultEl.textContent = "";
        });

        return iti;
    }

    return { init: init };
})();
