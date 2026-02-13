export function rupiah(value) {
    if (!value) return "Rp. 0";
    return (
        "Rp " +
        Number(value).toLocaleString("id-ID", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        })
    );
}

export function tanggal(tanggal) {
    return new Date(tanggal).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
}
