document.addEventListener("DOMContentLoaded", function () {
    const dataTable = document.getElementById("data-table");
    const searchInput = document.getElementById("search");
    const tbody = dataTable.getElementsByTagName("tbody")[0];

    searchInput.addEventListener("keyup", function () {
        const filter = searchInput.value.toLowerCase();
        const rows = tbody.getElementsByTagName("tr");
        let noDataFound = true;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const columns = row.getElementsByTagName("td");
            let shouldHide = true;

            for (let j = 0; j < columns.length; j++) {
                const column = columns[j];
                if (column) {
                    const text = column.textContent || column.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        shouldHide = false;
                        noDataFound = false;
                        break;
                    }
                }
            }

            row.style.display = shouldHide ? "none" : "";
        }

        // Tampilkan pesan "Tidak ada data" di tengah tabel jika tidak ada hasil pencarian
        if (noDataFound) {
            const noDataRow = document.createElement("tr");
            const noDataCell = document.createElement("td");
            noDataCell.setAttribute("colspan", "5"); // Sesuaikan jumlah kolom
            noDataCell.textContent = "Tidak ada data";
            noDataCell.style.textAlign = "center";
            noDataCell.style.backgroundColor = "#f5f5f9";
            noDataRow.appendChild(noDataCell);
            tbody.innerHTML = "";
            tbody.appendChild(noDataRow);
        }
    });
});