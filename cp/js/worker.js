let socket = null;

self.onmessage = function (event) {
    const { type, payload } = event.data;

    if (type === "INIT") {
        initSocket(payload);
    }
};

function initSocket(config) {
    importScripts("https://cdn.socket.io/4.5.4/socket.io.min.js");

    socket = io(config.url, {
        auth: {
            token: config.token
        }
    }); 

    socket.on("order:new", (data) => {
        self.postMessage({ type: "order:new", data: data });
    });

    socket.on("order:approve", (data) => {
        self.postMessage({ type: "order:approve", data: data });
    });

    socket.on("order:cancel", (data) => {
        self.postMessage({ type: "order:cancel", data: data });
    });

    socket.on("order:remove", (data) => {
        self.postMessage({ type: "order:remove", data: data });
    });
}