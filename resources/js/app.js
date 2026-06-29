import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toast").forEach(function (toastNode) {
        const toast = new bootstrap.Toast(toastNode);
        toast.show();
    });
});
