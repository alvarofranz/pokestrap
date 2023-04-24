/**
 * Get old index of post
 */
document.addEventListener('DOMContentLoaded', function () {

    // Get button element by ID
    const getOldIndexButton = document.getElementById('get-old-index');
    const displayOldIndexContainer = document.getElementById('display-old-index');

    // Listen for click event on button
    getOldIndexButton.addEventListener('click', function () {

        // Get post ID from data attribute
        const post_id = getOldIndexButton.dataset.postId;

        // Create a new XMLHttpRequest object
        const xhr = new XMLHttpRequest();

        // Set up AJAX request
        fetch(script_data.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'action': 'get_old_pokemon_index',
                'post_id': post_id
            })
        })
            .then(response => response.json())
            .then(data => {
                displayOldIndexContainer.innerHTML = '<p>Old Index: ' + data + '</p>';
            })
            .catch(error => {
                console.error(error);
            });



    });
});