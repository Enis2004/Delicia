var AdminServiceDelete = {
    currentEntity: null,
    currentEndpoint: null,
    idField: null,
    currentId: null,

    init: function() {
        var select = document.getElementById('entity-select-delete');
        if (select) {
            select.onchange = function() {
                AdminServiceDelete.onEntityChange();
            };
        }
    },

    onEntityChange: function() {
        var select = document.getElementById('entity-select-delete');
        var entity = select.value;
        
        AdminServiceDelete.currentEntity = entity;
        AdminServiceDelete.clearResults();
        
        if (!entity) {
            document.getElementById('admin-delete-form-container').innerHTML = '';
            return;
        }

        var configs = {
            'categories': { endpoint: 'categories', idField: 'category_id' },
            'menu-items': { endpoint: 'menu-items', idField: 'item_id' },
            'contacts': { endpoint: 'contacts', idField: 'contact_id' },
            'reservations': { endpoint: 'reservations', idField: 'reservation_id' },
            'users': { endpoint: 'users', idField: 'user_id' }
        };

        var config = configs[entity];
        if (!config) {
            document.getElementById('admin-delete-form-container').innerHTML = '<p>Invalid entity</p>';
            return;
        }

        AdminServiceDelete.currentEndpoint = config.endpoint;
        AdminServiceDelete.idField = config.idField;
        AdminServiceDelete.renderDeleteForm();
    },

    renderDeleteForm: function() {
        var container = document.getElementById('admin-delete-form-container');
        var idField = AdminServiceDelete.idField;
        var idFieldLabel = idField.replace('_', ' ');
        idFieldLabel = idFieldLabel.charAt(0).toUpperCase() + idFieldLabel.slice(1);
        
        var html = '<form id="admin-delete-form" class="admin-form" onsubmit="AdminServiceDelete.confirmDelete(); return false;">';
        html += '<div class="form-group mb-3">';
        html += '<label for="delete-id">' + idFieldLabel + ':</label>';
        html += '<input type="number" id="delete-id" class="form-control" placeholder="Enter ' + idField + '">';
        html += '</div>';
        html += '<button type="button" class="btn btn-secondary mb-2" onclick="AdminServiceDelete.loadItem()">Load Item to Delete</button>';
        html += '<div id="delete-item-preview"></div>';
        html += '<button type="submit" id="btn-confirm-delete" class="btn btn-danger" style="display:none;">Confirm Delete</button>';
        html += '</form>';

        container.innerHTML = html;
    },

    loadItem: function() {
        var idInput = document.getElementById('delete-id');
        var id = idInput.value;
        
        if (!id) {
            AdminServiceDelete.setStatus('Please enter an ID', 'error');
            return;
        }

        AdminServiceDelete.currentId = id;
        AdminServiceDelete.setStatus('Loading...', 'info');
        
        RestClient.get(AdminServiceDelete.currentEndpoint + '/' + id, function(data) {
            AdminServiceDelete.setStatus('Item loaded. Review below before deleting.', 'info');
            AdminServiceDelete.renderItemPreview(data);
            document.getElementById('btn-confirm-delete').style.display = 'block';
        }, function(error) {
            var errorMsg = 'Item not found';
            if (error.responseJSON && error.responseJSON.message) {
                errorMsg = error.responseJSON.message;
            } else if (error.responseText) {
                errorMsg = error.responseText;
            }
            AdminServiceDelete.setStatus('Error: ' + errorMsg, 'error');
            document.getElementById('delete-item-preview').innerHTML = '';
            document.getElementById('btn-confirm-delete').style.display = 'none';
        });
    },

    renderItemPreview: function(data) {
        var container = document.getElementById('delete-item-preview');
        if (!data) {
            container.innerHTML = '<p>No data found</p>';
            return;
        }

        var headers = [];
        for (var key in data) {
            headers.push(key);
        }
        
        var html = '<div class="alert alert-warning"><strong>Item to be deleted:</strong></div>';
        html += '<table class="table table-bordered table-sm"><tbody>';
        
        for (var i = 0; i < headers.length; i++) {
            var header = headers[i];
            var value = data[header];
            html += '<tr><th>' + header + '</th><td>' + (value !== null && value !== undefined ? value : '') + '</td></tr>';
        }
        
        html += '</tbody></table>';
        container.innerHTML = html;
    },

    confirmDelete: function() {
        var id = AdminServiceDelete.currentId;
        if (!id) {
            var idInput = document.getElementById('delete-id');
            id = idInput ? idInput.value : null;
        }
        
        if (!id) {
            AdminServiceDelete.setStatus('Please enter an ID first', 'error');
            return;
        }

        AdminServiceDelete.setStatus('Deleting...', 'info');
        
        RestClient.delete(AdminServiceDelete.currentEndpoint + '/' + id, null, function(response) {
            AdminServiceDelete.setStatus('Successfully deleted', 'success');
            var form = document.getElementById('admin-delete-form');
            if (form) form.reset();
            document.getElementById('delete-item-preview').innerHTML = '';
            document.getElementById('btn-confirm-delete').style.display = 'none';
            AdminServiceDelete.currentId = null;
            AdminServiceDelete.renderTable([{ id: id, status: 'deleted', entity: AdminServiceDelete.currentEntity }]);
        }, function(error) {
            var errorMsg = 'Error deleting item';
            if (error.responseJSON && error.responseJSON.message) {
                errorMsg = error.responseJSON.message;
            } else if (error.responseText) {
                errorMsg = error.responseText;
            }
            AdminServiceDelete.setStatus('Error: ' + errorMsg, 'error');
        });
    },

    renderTable: function(data) {
        var container = document.getElementById('admin-table-container');
        if (!data || (data.length === 0 && !Array.isArray(data))) {
            container.innerHTML = '<p>No data to display</p>';
            return;
        }

        var rows = Array.isArray(data) ? data : [data];
        if (rows.length === 0) {
            container.innerHTML = '<p>No results found</p>';
            return;
        }

        var headers = [];
        for (var key in rows[0]) {
            headers.push(key);
        }
        
        var html = '<h3>Delete Result:</h3><table class="table table-striped table-bordered"><thead><tr>';
        for (var i = 0; i < headers.length; i++) {
            html += '<th>' + headers[i] + '</th>';
        }
        html += '</tr></thead><tbody>';

        for (var j = 0; j < rows.length; j++) {
            html += '<tr>';
            for (var k = 0; k < headers.length; k++) {
                var value = rows[j][headers[k]];
                html += '<td>' + (value !== null && value !== undefined ? value : '') + '</td>';
            }
            html += '</tr>';
        }

        html += '</tbody></table>';
        container.innerHTML = html;
    },

    setStatus: function(message, type) {
        var responseBox = document.getElementById('admin-response-box');
        if (!responseBox) return;

        var typeClass = 'alert-info';
        if (type === 'error') {
            typeClass = 'alert-danger';
        } else if (type === 'success') {
            typeClass = 'alert-success';
        } else if (type === 'warning') {
            typeClass = 'alert-warning';
        }
        
        responseBox.innerHTML = '<div class="alert ' + typeClass + '">' + message + '</div>';
        
        if (type === 'info') {
            setTimeout(function() {
                responseBox.innerHTML = '';
            }, 3000);
        }
    },

    clearResults: function() {
        var tableContainer = document.getElementById('admin-table-container');
        var responseBox = document.getElementById('admin-response-box');
        
        if (tableContainer) tableContainer.innerHTML = '';
        if (responseBox) responseBox.innerHTML = '';
    }
};
