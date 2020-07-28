
function TwitterClient(host) {

    const conn = new WebSocket('ws://'+ host);

    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        console.log(e.data);
    };

    return {
    }
}
