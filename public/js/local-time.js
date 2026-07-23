document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.local-time').forEach(function (el) {
        var utc = el.getAttribute('data-utc');
        if (!utc) {
            return;
        }

        var date = new Date(utc);
        if (isNaN(date.getTime())) {
            return;
        }

        var dateOnly = el.getAttribute('data-date-only') !== null;

        if (dateOnly) {
            el.textContent = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
            });
        } else {
            el.textContent = date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
            });
        }
    });
});
