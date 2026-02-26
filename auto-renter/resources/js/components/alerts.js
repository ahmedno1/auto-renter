import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

const getDetailValue = (detail, key) => {
    if (!detail || typeof detail !== "object") {
        return undefined;
    }

    if (Object.prototype.hasOwnProperty.call(detail, key)) {
        return detail[key];
    }

    if (Object.prototype.hasOwnProperty.call(detail, String(key))) {
        return detail[String(key)];
    }

    return undefined;
};

const normalizeDetail = (detail = {}) => {
    if (typeof detail === "string") {
        return { message: detail };
    }

    if (Array.isArray(detail)) {
        if (detail.length === 0) {
            return {};
        }
        if (typeof detail[0] === "string") {
            return { message: detail[0] };
        }
        if (detail[0] && typeof detail[0] === "object") {
            return detail[0];
        }
        return { message: String(detail[0]) };
    }

    if (!detail || typeof detail !== "object") {
        return { message: String(detail) };
    }

    const message =
        detail.message ??
        detail.text ??
        getDetailValue(detail, 0) ??
        getDetailValue(detail, "message") ??
        undefined;

    const type = detail.type ?? detail.icon ?? getDetailValue(detail, "type") ?? undefined;
    const title = detail.title ?? getDetailValue(detail, "title") ?? undefined;

    return {
        message: message !== undefined ? String(message) : undefined,
        type: type !== undefined ? String(type) : undefined,
        title: title !== undefined ? String(title) : undefined,
    };
};

const toSwalIcon = (type) => {
    const value = (type ?? "").toLowerCase();
    if (["success", "error", "warning", "info", "question"].includes(value)) {
        return value;
    }
    if (value === "danger") {
        return "error";
    }
    return "info";
};

const isDark = () => document.documentElement.classList.contains("dark");

const toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    showCloseButton: true,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (element) => {
        element.addEventListener("mouseenter", Swal.stopTimer);
        element.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

const fireToast = (detail) => {
    const payload = normalizeDetail(detail);
    const message = payload.message ?? "Done";
    const icon = toSwalIcon(payload.type);
    const title = payload.title ?? message;

    toast.fire({
        icon,
        title,
        text: payload.title ? message : undefined,
        background: isDark() ? "#0a0a0a" : "#ffffff",
        color: isDark() ? "#fafafa" : "#111827",
    });
};

const fireAlert = (detail) => {
    const payload = normalizeDetail(detail);
    const message = payload.message ?? "";
    const icon = toSwalIcon(payload.type);

    Swal.fire({
        icon,
        title: payload.title ?? undefined,
        text: message || undefined,
        confirmButtonText: "OK",
        background: isDark() ? "#0a0a0a" : "#ffffff",
        color: isDark() ? "#fafafa" : "#111827",
    });
};

window.addEventListener("toast", (event) => fireToast(event.detail));
window.addEventListener("alert", (event) => fireAlert(event.detail));

document.addEventListener("livewire:init", () => {
    const Livewire = window.Livewire;
    if (!Livewire?.on) {
        return;
    }

    Livewire.on("toast", (detail) => fireToast(detail));
    Livewire.on("alert", (detail) => fireAlert(detail));
});
