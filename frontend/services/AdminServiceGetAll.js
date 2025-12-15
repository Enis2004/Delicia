var AdminServiceGetAll = {
    init: function() {
        console.log("AdminServiceGetAll initialized");
        this.attachListener('btn-getall-execute', 'click', this.executeGetAll);
        this.attachListener('entity-select-getall', 'change', this.onEntityChange);
    },

    attachListener: function(id, event, handler) {
        const el = document.getElementById(id);
        if (el) {
            el.removeEventListener(event, handler);
            el.addEventListener(event, handler);
        }
    },

    onEntityChange: function() {
        AdminServiceGetAll.clearResults();
    },

    executeGetAll: function() {
        const entity = document.getElementById('entity-select-getall').value;
        if (!entity) {
            AdminServiceGetAll.setStatus('Please select an entity', 'error');
            return;
        }

        AdminServiceGetAll.setStatus('Loading...', 'info');
        AdminServiceGetAll.getAll(entity);
    },

    getAll: function(entity) {
        const endpoints = {
            'categories': 'categories',
            'menu-items': 'menu-items',
            'contacts': 'contacts',
            'reservations': 'reservations',
            'users': 'users'
        };

        const endpoint = endpoints[entity];
        if (!endpoint) {
            AdminServiceGetAll.setStatus('Invalid entity selected', 'error');
            return;
        }

        RestClient.get(endpoint, function(data) {
            AdminServiceGetAll.setStatus('Data loaded successfully', 'success');
            AdminServiceGetAll.renderTable(data);
        }, function(error) {
            AdminServiceGetAll.setStatus('Error loading data: ' + (error.responseJSON?.message || error.responseText || 'Unknown error'), 'error');
            console.error(error);
        });
    },

    renderTable: function(data) {
        const container = document.getElementById('admin-table-container');
        if (!data || (Array.isArray(data) && data.length === 0)) {
            container.innerHTML = '<p>No data found</p>';
            return;
        }

        const rows = Array.isArray(data) ? data : [data];
        if (rows.length === 0) {
            container.innerHTML = '<p>No results found</p>';
            return;
        }

        const headers = Object.keys(rows[0]);
        let html = '<table class="table table-striped table-bordered"><thead><tr>';
        headers.forEach(header => {
            html += '<th>' + header + '</th>';
        });
        html += '</tr></thead><tbody>';

        rows.forEach(row => {
            html += '<tr>';
            headers.forEach(header => {
                const value = row[header];
                html += '<td>' + (value !== null && value !== undefined ? value : '') + '</td>';
            });
            html += '</tr>';
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    },

    setStatus: function(message, type) {
        const responseBox = document.getElementById('admin-response-box');
        if (!responseBox) return;

        const typeClass = type === 'error' ? 'alert-danger' : type === 'success' ? 'alert-success' : 'alert-info';
        responseBox.innerHTML = '<div class="alert ' + typeClass + '">' + message + '</div>';
        
        if (type === 'info') {
            setTimeout(function() {
                if (responseBox.innerHTML.includes(message)) {
                    responseBox.innerHTML = '';
                }
            }, 3000);
        }
    },

    clearResults: function() {
        document.getElementById('admin-table-container').innerHTML = '';
        document.getElementById('admin-response-box').innerHTML = '';
    }
};

