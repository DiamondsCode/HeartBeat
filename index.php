<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Health Monitor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Server Health Monitor</h1>
    <div class="separator"></div>
    <div id="serverList" class="server-list"></div>

    <script>
        async function fetchHeartbeatData() {
            const response = await fetch('heartbeat_data.php');
            const data = await response.json();
            return data;
        }

        function getHealthStatus(lastPingTime) {
            const now = Math.floor(Date.now() / 1000);
            const timeDiff = now - lastPingTime;

            if (timeDiff <= 120) {
                return { status: 'HEALTHY', class: 'healthy' };
            } else if (timeDiff <= 300) {
                return { status: 'UNHEALTHY', class: 'unhealthy' };
            } else {
                return { status: 'FATAL', class: 'fatal' };
            }
        }

        function renderServerList(data) {
            const serverListDiv = document.getElementById('serverList');
            serverListDiv.innerHTML = '';

            Object.keys(data).forEach(server => {
                const serverDiv = document.createElement('div');
                serverDiv.classList.add('server-card');

                const latestHeartbeat = data[server][data[server].length - 1].time;
                const { status, class: statusClass } = getHealthStatus(latestHeartbeat);

                serverDiv.innerHTML = `
                    <div class="server-name">${server}</div>
                    <div class="status ${statusClass}">${status}</div>
                    <div class="timestamp">Last Pulse: ${new Date(latestHeartbeat * 1000).toLocaleString('en-US', { timeZone: 'UTC' })}</div>
                `;

                serverListDiv.appendChild(serverDiv);
            });
        }

        async function updateHeartbeat() {
            const data = await fetchHeartbeatData();
            renderServerList(data);
        }

        setInterval(updateHeartbeat, 10000);
        updateHeartbeat();
    </script>
</body>
</html>
