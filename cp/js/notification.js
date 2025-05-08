setInterval(sender, 500)

function sender()
{
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();
    
    // Configure the request: POST method and endpoint URL
    var url = '../ajax/check_unmarked.php';
    xhr.open('POST', url, true);
    
    // Define a callback function to handle the response
    xhr.onload = function() {
      // Check if the request was successful
      if (xhr.status >= 200 && xhr.status < 300) {
        // Parse the JSON response
        var response = JSON.parse(xhr.responseText);
        // Handle the response data
        postMessage(response)
    }
    };
    
    // Send the request with the data
    xhr.send();
}