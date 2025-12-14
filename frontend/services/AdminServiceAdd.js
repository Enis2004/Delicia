var AdminServiceAdd = {
    IMGBB_API_KEY: 'ffe5b92130381d33537ee0aabb1bb40d',
    init: function() {
        console.log("AdminServiceAdd initialized");
        this.attachListener('entity-select-add', 'change', this.onEntityChange);
    },

    attachListener: function(id, event, handler) {
        const el = document.getElementById(id);
        if (el) {
            el.removeEventListener(event, handler);
            el.addEventListener(event, handler);
        }
    },

    onEntityChange: function() {
        const entity = document.getElementById('entity-select-add').value;
        AdminServiceAdd.renderForm(entity);
        AdminServiceAdd.clearResults();
    },

    renderForm: function(entity) {
        const container = document.getElementById('admin-add-form-container');
        if (!entity) {
            container.innerHTML = '';
            return;
        }

        const formConfigs = {
            'categories': {
                fields: [
                    { name: 'name', label: 'Name', type: 'text', required: true }
                ],
                endpoint: 'categories'
            },
            'menu-items': {
                fields: [
                    { name: 'name', label: 'Name', type: 'text', required: true },
                    { name: 'description', label: 'Description', type: 'text', required: false },
                    { name: 'price', label: 'Price', type: 'number', step: '0.01', required: true },
                    { name: 'category_id', label: 'Category ID', type: 'number', required: false }
                ],
                endpoint: 'menu-items'
            },
            'contacts': {
                fields: [
                    { name: 'user_name', label: 'User Name', type: 'text', required: true },
                    { name: 'user_email', label: 'User Email', type: 'email', required: true },
                    { name: 'subject', label: 'Subject', type: 'text', required: false },
                    { name: 'message', label: 'Message', type: 'textarea', required: false }
                ],
                endpoint: 'contacts'
            },
            'reservations': {
                fields: [
                    { name: 'user_id', label: 'User ID', type: 'number', required: false },
                    { name: 'date', label: 'Date (YYYY-MM-DD)', type: 'date', required: true },
                    { name: 'time', label: 'Time (HH:MM:SS)', type: 'time', required: true }
                ],
                endpoint: 'reservations'
            },
            'users': {
                fields: [
                    { name: 'name', label: 'Name', type: 'text', required: true },
                    { name: 'email', label: 'Email', type: 'email', required: true },
                    { name: 'password', label: 'Password', type: 'password', required: true },
                    { name: 'role', label: 'Role (admin/user)', type: 'text', required: false }
                ],
                endpoint: 'users'
            }
        };

        const config = formConfigs[entity];
        if (!config) {
            container.innerHTML = '<p>Invalid entity</p>';
            return;
        }
        if (entity === 'menu-items') {
            container.innerHTML = '' +
                '<form id="admin-add-form-menu" name="admin-add-form-menu" class="admin-form">' +
                '  <div class="form-group mb-3">' +
                '    <label for="add-mi-name" class="text-dark">Name <span class="text-danger">*</span>:</label>' +
                '    <input type="text" id="add-mi-name" name="name" class="form-control" required>' +
                '  </div>' +
                '  <div class="form-group mb-3">' +
                '    <label for="add-mi-description" class="text-dark">Description:</label>' +
                '    <input type="text" id="add-mi-description" name="description" class="form-control">' +
                '  </div>' +
                '  <div class="form-group mb-3">' +
                '    <label for="add-mi-price" class="text-dark">Price <span class="text-danger">*</span>:</label>' +
                '    <input type="number" step="0.01" id="add-mi-price" name="price" class="form-control" required>' +
                '  </div>' +
                '  <div class="form-group mb-3">' +
                '    <label for="add-mi-category" class="text-dark">Category ID:</label>' +
                '    <input type="number" id="add-mi-category" name="category_id" class="form-control">' +
                '  </div>' +
                '  <div class="form-group mb-3">' +
                '    <label for="add-mi-image" class="text-dark">Image (jpg/png) <span class="text-danger">*</span>:</label>' +
                '    <input type="file" accept="image/*" id="add-mi-image" name="image" class="form-control" required>' +
                '  </div>' +
                '  <button type="submit" class="btn btn-primary">Add menu item</button>' +
                '</form>';
        } else {
            let html = '<form id="admin-add-form" class="admin-form">';
            config.fields.forEach(field => {
                html += '<div class="form-group mb-3">';
                html += '<label for="add-' + field.name + '" class="text-dark">' + field.label + (field.required ? ' <span class="text-danger">*</span>' : '') + ':</label>';
                if (field.type === 'textarea') {
                    html += '<textarea id="add-' + field.name + '" class="form-control" name="' + field.name + '"' + (field.required ? ' required' : '') + '></textarea>';
                } else {
                    html += '<input type="' + field.type + '" id="add-' + field.name + '" class="form-control" name="' + field.name + '"';
                    if (field.step) html += ' step="' + field.step + '"';
                    if (field.required) html += ' required';
                    html += '>';
                }
                html += '</div>';
            });
            html += '<button type="submit" class="btn btn-primary">Add ' + entity + '</button>';
            html += '</form>';
            container.innerHTML = html;
        }
        setTimeout(function() {
            if (entity === 'menu-items') {
                var formMi = document.getElementById('admin-add-form-menu');
                if (formMi) {
                    var clonedMi = formMi.cloneNode(true);
                    formMi.parentNode.replaceChild(clonedMi, formMi);
                    document.getElementById('admin-add-form-menu').addEventListener('submit', function(e) {
                        AdminServiceAdd.submitAddMenuItem(e);
                    });
                }
            } else {
                const form = document.getElementById('admin-add-form');
                if (form) {
                    const newForm = form.cloneNode(true);
                    form.parentNode.replaceChild(newForm, form);
                    document.getElementById('admin-add-form').addEventListener('submit', function(e) {
                        e.preventDefault();
                        AdminServiceAdd.submitAdd(entity, config.endpoint);
                    });
                }
            }
        }, 100);
    },

    uploadImageToImgBB: function(file, onSuccess, onError) {
        var formData = new FormData();
        formData.append('image', file);
        fetch('https://api.imgbb.com/1/upload?key=' + AdminServiceAdd.IMGBB_API_KEY, {
            method: 'POST',
            body: formData
        })
        .then(function(response){ return response.json(); })
        .then(function(result){
            if (result && result.success && result.data && result.data.url) {
                if (onSuccess) onSuccess(result.data.url);
            } else {
                if (onError) onError('ImgBB upload failed');
            }
        })
        .catch(function(){
            if (onError) onError('Network error during image upload');
        });
    },

    submitAddMenuItem: function(e) {
        e.preventDefault();
        var name = document.getElementById('add-mi-name').value.trim();
        var description = document.getElementById('add-mi-description').value.trim();
        var priceVal = document.getElementById('add-mi-price').value;
        var categoryVal = document.getElementById('add-mi-category').value;
        var file = document.getElementById('add-mi-image').files[0];

        if (!name || !priceVal || !file) {
            AdminServiceAdd.setStatus('Name, price, and image are required', 'error');
            return;
        }

        var data = {
            name: name,
            description: description,
            price: parseFloat(priceVal)
        };
        if (categoryVal) {
            var cid = parseInt(categoryVal);
            if (!isNaN(cid)) data.category_id = cid;
        }

        AdminServiceAdd.setStatus('Uploading image...', 'info');
        AdminServiceAdd.uploadImageToImgBB(file, function(url){
            data.image_url = url;
            AdminServiceAdd.setStatus('Adding menu item...', 'info');
            RestClient.post('menu-items', data, function(response){
                AdminServiceAdd.setStatus('Successfully added', 'success');
                AdminServiceAdd.renderTable([response]);
                var form = document.getElementById('admin-add-form-menu');
                if (form) form.reset();
            }, function(error){
                var errorMsg = error.responseJSON?.message || error.responseText || 'Error adding item';
                AdminServiceAdd.setStatus('Error: ' + errorMsg, 'error');
            });
        }, function(msg){
            AdminServiceAdd.setStatus(msg, 'error');
        });
    },

    submitAdd: function(entity, endpoint) {
        const form = document.getElementById('admin-add-form');
        if (!form) {
            AdminServiceAdd.setStatus('Form not found', 'error');
            return;
        }

        const data = {};
        const formConfigs = {
            'categories': { fields: ['name'] },
            'menu-items': { fields: ['name', 'description', 'price', 'category_id'] },
            'contacts': { fields: ['user_name', 'user_email', 'subject', 'message'] },
            'reservations': { fields: ['user_id', 'date', 'time'] },
            'users': { fields: ['name', 'email', 'password', 'role'] }
        };

        const config = formConfigs[entity];
        if (!config) {
            AdminServiceAdd.setStatus('Invalid entity configuration', 'error');
            return;
        }

        
        let hasRequiredError = false;
        config.fields.forEach(field => {
            const input = document.getElementById('add-' + field);
            if (input) {
                let value = input.value.trim();
                
                
                if (input.hasAttribute('required') && value === '') {
                    hasRequiredError = true;
                    return;
                }
                if (value !== '') {
                    if (field.includes('_id') || field === 'price' || field === 'user_id') {
                        const numValue = field === 'price' ? parseFloat(value) : parseInt(value);
                        if (!isNaN(numValue)) {
                            data[field] = numValue;
                        }
                    } else {
                        data[field] = value;
                    }
                }
            }
        });

        if (hasRequiredError) {
            AdminServiceAdd.setStatus('Please fill all required fields', 'error');
            return;
        }

        if (Object.keys(data).length === 0) {
            AdminServiceAdd.setStatus('Please fill at least one field', 'error');
            return;
        }

        AdminServiceAdd.setStatus('Adding...', 'info');
        
        RestClient.post(endpoint, data, function(response) {
            AdminServiceAdd.setStatus('Successfully added', 'success');
            AdminServiceAdd.renderTable([response]);
            form.reset();
        }, function(error) {
            const errorMsg = error.responseJSON?.message || error.responseText || 'Error adding item';
            AdminServiceAdd.setStatus('Error: ' + errorMsg, 'error');
            console.error(error);
        });
    },

    renderTable: function(data) {
        const container = document.getElementById('admin-table-container');
        if (!data || (Array.isArray(data) && data.length === 0)) {
            container.innerHTML = '<p>No data to display</p>';
            return;
        }

        const rows = Array.isArray(data) ? data : [data];
        const headers = Object.keys(rows[0]);
        
        let html = '<h3>Added Item:</h3><table class="table table-striped table-bordered"><thead><tr>';
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

