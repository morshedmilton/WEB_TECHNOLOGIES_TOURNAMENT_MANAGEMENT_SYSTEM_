function searchTournament() {
    let query = document.getElementById('search_box').value;
    let result_area = document.getElementById('search_results');
    let main_table = document.getElementById('main_tournament_table');

    if (query == "") {
        result_area.innerHTML = "";
        main_table.style.display = "table";
        return;
    }

    let searchObj = { 'query': query };
    let data = JSON.stringify(searchObj);

    let xhttp = new XMLHttpRequest();
    xhttp.open('POST', '../controller/searchController.php', true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('search_data=' + data);

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            main_table.style.display = "none";

            let tournaments = JSON.parse(this.responseText);

            if (tournaments.length > 0) {
                let html = `<table border='1' cellspacing='0' cellpadding='10' style='width: 100%; text-align: center;'>
                                <tr style='background-color: #f2f2f2;'>
                                    <th>ID</th><th>Title</th><th>Category</th><th>Status</th><th>Actions</th>
                                </tr>`;

                for (let i = 0; i < tournaments.length; i++) {
                    html += `<tr>
                                <td>${tournaments[i].id}</td>
                                <td>${tournaments[i].title}</td>
                                <td>${tournaments[i].category}</td>
                                <td>${tournaments[i].status}</td>
                                <td>
                                    <a href='detailsTournament.php?id=${tournaments[i].id}'>View</a>
                                </td>
                             </tr>`;
                }
                html += "</table>";
                result_area.innerHTML = html;
            } else {
                result_area.innerHTML = "<p style='color: red; text-align: center;'>No tournaments found!</p>";
            }
        }
    }
}
