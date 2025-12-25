function searchTournament() {
    let query = document.getElementById('search_box').value;
    let result_area = document.getElementById('search_results');
    let main_table = document.getElementById('main_tournament_table');

    // If the search box is empty, show the previous table
    if (query == "") {
        result_area.innerHTML = "";
        main_table.style.display = "table";
        return;
    }

    // Sending AJAX Request
    let xhttp = new XMLHttpRequest();
    xhttp.open('GET', '../controller/searchController.php?query=' + query, true);
    xhttp.send();

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            main_table.style.display = "none"; // Hide the original table
            result_area.innerHTML = this.responseText; // Show search results
        }
    }
}
