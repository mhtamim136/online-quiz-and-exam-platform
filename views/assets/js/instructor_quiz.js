document.addEventListener('DOMContentLoaded', function () {
    var radios = document.querySelectorAll('.instructor-correct-radio');
    for (var i = 0; i < radios.length; i++) {
        radios[i].addEventListener('change', function () {
            var rows = document.querySelectorAll('.instructor-option-row');
            for (var j = 0; j < rows.length; j++) {
                rows[j].style.background = '';
            }
            this.parentNode.style.background = '#e0f2fe';
        });
    }
});
