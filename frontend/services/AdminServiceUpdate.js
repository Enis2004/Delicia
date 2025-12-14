var AdminServiceUpdate = {
    IMGBB_API_KEY: 'ffe5b92130381d33537ee0aabb1bb40d',
    currentEntity: null,
    currentEndpoint: null,
    idField: null,
    currentId: null,

    init: function() {
        var select = document.getElementById('entity-select-update');
        if (select) {
            select.onchange = function() {
                AdminServiceUpdate.onEntityChange();
            };
        }
    },

    onEntityChange: function() {
        var select = document.getElementById('entity-select-update');
        var entity = select.value;
        
        AdminServiceUpdate.currentEntity = entity;
        AdminServiceUpdate.clearResults();
        
        if (!entity) {
            document.getElementById('admin-update-form-container').innerHTML = '';
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
            document.getElementById('admin-update-form-container').innerHTML = '<p>Invalid entity</p>';
            return;
        }

        AdminServiceUpdate.currentEndpoint = config.endpoint;
        AdminServiceUpdate.idField = config.idField;
        AdminServiceUpdate.renderIdForm();
    },

    renderIdForm: function() {
        var container = document.getElementById('admin-update-form-container');
        var idField = AdminServiceUpdate.idField;
        var idFieldLabel = idField.replace('_', ' ');
        idFieldLabel = idFieldLabel.charAt(0).toUpperCase() + idFieldLabel.slice(1);
        
        var html = '<div class="form-group mb-3">';
        html += '<label for="update-id">' + idFieldLabel + ':</label>';
        html += '<input type="number" id="update-id" class="form-control" placeholder="Enter ' + idField + '">';
        html += '<button type="button" class="btn btn-secondary mt-2" onclick="AdminServiceUpdate.loadItem()">Load Item</button>';
        html += '</div>';
        html += '<div id="update-form-fields"></div>';

        container.innerHTML = html;
    },

    loadItem: function() {
        var idInput = document.getElementById('update-id');
        var id = idInput.value;
        
        if (!id) {
            AdminServiceUpdate.setStatus('Please enter an ID', 'error');
            return;
        }

        AdminServiceUpdate.currentId = id;
        AdminServiceUpdate.setStatus('Loading...', 'info');
        
        RestClient.get(AdminServiceUpdate.currentEndpoint + '/' + id, function(data) {
            AdminServiceUpdate.setStatus('Item loaded', 'success');
            AdminServiceUpdate.renderUpdateForm(data);
        }, function(error) {
            var errorMsg = 'Item not found';
            if (error.responseJSON && error.responseJSON.message) {
                errorMsg = error.responseJSON.message;
            } else if (error.responseText) {
                errorMsg = error.responseText;
            }
            AdminServiceUpdate.setStatus('Error: ' + errorMsg, 'error');
        });
    },

    renderUpdateForm: function(data) {
        var container = document.getElementById('update-form-fields');
        if (!data) {
            container.innerHTML = '<p>No data found</p>';
            return;
        }

        if (AdminServiceUpdate.currentEntity === 'menu-items') {
            var htmlMi = '' +
                '<form id="admin-update-form-menu" class="admin-form" onsubmit="AdminServiceUpdate.submitUpdateMenuItem(); return false;">' +
                '  <div class="form-group mb-3">' +
                '    <label for="update-mi-name">Name:</label>' +
                '    <input type="text" id="update-mi-name" class="form-control" value="' + (data.name || '') + '">' +
                '  </div>' +
                '  <div class="form-group mb-3">' +
                '    <label for="update-mi-description">Description:</label>' +
                '    <input type="text" id="update-mi-description" class="form-control" value="' + (data.description || '') + '">' +
                '  </div>' +
                '  <div class="form-group mb-3">' +
                '    <label for="update-mi-price">Price:</label>' +
                '    <input type="number" step="0.01" id="update-mi-price" class="form-control" value="' + (data.price || '') + '">' +
                '  </div>' +
                '  <div class="form-group mb-3">' +
                '    <label for="update-mi-category">Category ID:</label>' +
                '    <input type="number" id="update-mi-category" class="form-control" value="' + (data.category_id || '') + '">' +
                '  </div>' +
                '  <div class="form-group mb-3">' +
                '    <label for="update-mi-image">New Image (optional):</label>' +
                '    <input type="file" accept="image/*" id="update-mi-image" class="form-control">' +
                '  </div>' +
                '  <button type="submit" class="btn btn-primary">Update</button>' +
                '</form>';
            container.innerHTML = htmlMi;
            return;
        }

        var fields = [];
        if (AdminServiceUpdate.currentEntity === 'categories') {
            fields = ['name'];
        } else if (AdminServiceUpdate.currentEntity === 'contacts') {
            fields = ['user_name', 'user_email', 'subject', 'message'];
        } else if (AdminServiceUpdate.currentEntity === 'reservations') {
            fields = ['user_id', 'date', 'time'];
        } else if (AdminServiceUpdate.currentEntity === 'users') {
            fields = ['name', 'email', 'password', 'role'];
        }

        var html = '<form id="admin-update-form" class="admin-form" onsubmit="AdminServiceUpdate.submitUpdate(); return false;">';
        
        for (var i = 0; i < fields.length; i++) {
            var field = fields[i];
            var value = data[field] || '';
            var fieldLabel = field.replace('_', ' ');
            fieldLabel = fieldLabel.charAt(0).toUpperCase() + fieldLabel.slice(1);
            
            html += '<div class="form-group mb-3">';
            html += '<label for="update-' + field + '">' + fieldLabel + ':</label>';
            
            if (field === 'message') {
                html += '<textarea id="update-' + field + '" class="form-control">' + value + '</textarea>';
            } else if (field === 'date') {
                html += '<input type="date" id="update-' + field + '" class="form-control" value="' + value + '">';
            } else if (field === 'time') {
                html += '<input type="time" id="update-' + field + '" class="form-control" value="' + value + '">';
            } else if (field === 'password') {
                html += '<input type="password" id="update-' + field + '" class="form-control" placeholder="Leave empty to keep current">';
            } else {
                var inputType = 'text';
                if (field.includes('_id') || field === 'price' || field === 'user_id') {
                    inputType = 'number';
                } else if (field === 'email') {
                    inputType = 'email';
                }
                html += '<input type="' + inputType + '" id="update-' + field + '" class="form-control" value="' + value + '">';
            }
            html += '</div>';
        }
        
        html += '<button type="submit" class="btn btn-primary">Update</button>';
        html += '</form>';

        container.innerHTML = html;
    },

    submitUpdate: function() {
        var id = AdminServiceUpdate.currentId;
        if (!id) {
            AdminServiceUpdate.setStatus('Please load an item first', 'error');
            return;
        }

        if (AdminServiceUpdate.currentEntity === 'menu-items') {
            AdminServiceUpdate.submitUpdateMenuItem();
            return;
        }

        var fields = [];
        if (AdminServiceUpdate.currentEntity === 'categories') {
            fields = ['name'];
        } else if (AdminServiceUpdate.currentEntity === 'contacts') {
            fields = ['user_name', 'user_email', 'subject', 'message'];
        } else if (AdminServiceUpdate.currentEntity === 'reservations') {
            fields = ['user_id', 'date', 'time'];
        } else if (AdminServiceUpdate.currentEntity === 'users') {
            fields = ['name', 'email', 'password', 'role'];
        }

        var data = {};
        var hasChanges = false;

        for (var i = 0; i < fields.length; i++) {
            var field = fields[i];
            var input = document.getElementById('update-' + field);
            
            if (input) {
                var value = input.value.trim();
                
                if (field === 'password' && value === '') {
                    continue;
                }
                
                if (value !== '') {
                    hasChanges = true;
                    
                    if (field.includes('_id') || field === 'price' || field === 'user_id') {
                        var numValue = field === 'price' ? parseFloat(value) : parseInt(value);
                        if (!isNaN(numValue)) {
                            data[field] = numValue;
                        } else {
                            data[field] = value;
                        }
                    } else {
                        data[field] = value;
                    }
                }
            }
        }

        if (!hasChanges) {
            AdminServiceUpdate.setStatus('No changes entered', 'error');
            return;
        }

        AdminServiceUpdate.setStatus('Updating...', 'info');
        
        RestClient.request(AdminServiceUpdate.currentEndpoint + '/' + id, 'PUT', data, function(response) {
            AdminServiceUpdate.setStatus('Successfully updated', 'success');
            AdminServiceUpdate.renderTable([response]);
        }, function(error) {
            var errorMsg = 'Error updating item';
            if (error.responseJSON && error.responseJSON.message) {
                errorMsg = error.responseJSON.message;
            } else if (error.responseText) {
                errorMsg = error.responseText;
            }
            AdminServiceUpdate.setStatus('Error: ' + errorMsg, 'error');
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
        
        var html = '<h3>Updated Item:</h3><table class="table table-striped table-bordered"><thead><tr>';
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
        var formFields = document.getElementById('update-form-fields');
        
        if (tableContainer) tableContainer.innerHTML = '';
        if (responseBox) responseBox.innerHTML = '';
        if (formFields) formFields.innerHTML = '';
    }

    ,submitUpdateMenuItem: function() {
        var id = AdminServiceUpdate.currentId;
        if (!id) {
            AdminServiceUpdate.setStatus('Please load an item first', 'error');
            return;
        }

        var name = document.getElementById('update-mi-name').value.trim();
        var description = document.getElementById('update-mi-description').value.trim();
        var priceVal = document.getElementById('update-mi-price').value;
        var categoryVal = document.getElementById('update-mi-category').value;
        var file = document.getElementById('update-mi-image').files[0];

        var data = {};
        if (name) data.name = name;
        if (description) data.description = description;
        if (priceVal) {
            var priceNum = parseFloat(priceVal);
            if (!isNaN(priceNum)) data.price = priceNum;
        }
        if (categoryVal) {
            var cid = parseInt(categoryVal);
            if (!isNaN(cid)) data.category_id = cid;
        }

        var proceedUpdate = function(){
            AdminServiceUpdate.setStatus('Updating...', 'info');
            RestClient.request('menu-items/' + id, 'PUT', data, function(response){
                AdminServiceUpdate.setStatus('Successfully updated', 'success');
                AdminServiceUpdate.renderTable([response]);
            }, function(error){
                var errorMsg = error.responseJSON && error.responseJSON.message ? error.responseJSON.message : (error.responseText || 'Error updating item');
                AdminServiceUpdate.setStatus('Error: ' + errorMsg, 'error');
            });
        };

        if (file) {
            AdminServiceUpdate.setStatus('Uploading image...', 'info');
            var formData = new FormData();
            formData.append('image', file);
            fetch('https://api.imgbb.com/1/upload?key=' + AdminServiceUpdate.IMGBB_API_KEY, {
                method: 'POST',
                body: formData
            })
            .then(function(res){ return res.json(); })
            .then(function(result){
                if (result && result.success && result.data && result.data.url) {
                    data.image_url = result.data.url;
                    proceedUpdate();
                } else {
                    AdminServiceUpdate.setStatus('ImgBB upload failed', 'error');
                }
            })
            .catch(function(){
                AdminServiceUpdate.setStatus('Network error during image upload', 'error');
            });
        } else {
            proceedUpdate();
        }
    }
};
