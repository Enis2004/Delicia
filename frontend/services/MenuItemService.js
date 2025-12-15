var MenuItemsService = {
    state: {
        items: [],
        categories: [],
        container: null,
        loading: null
    },

    init: function() {
        this.state.container = document.getElementById('menu-items-container');
        this.state.loading = document.getElementById('menu-loading');

        if (!this.state.container) {
            return;
        }

        this.renderLoading('Loading menu...');
        this.fetchCategoriesThenItems();
    },

    fetchCategoriesThenItems: function() {
        var self = this;
        self.fetchCategories(function() {
            self.fetchItems();
        }, function() {
            self.fetchItems();
        });
    },

    fetchCategories: function(onSuccess, onFail) {
        var self = this;
        if (typeof RestClient === 'undefined') {
            if (onFail) onFail();
            return;
        }

        RestClient.get('categories', function(data) {
            self.state.categories = Array.isArray(data) ? data : [];
            if (onSuccess) onSuccess();
        }, function(err) {
            self.state.categories = [];
            if (onFail) onFail(err);
        });
    },

    fetchItems: function() {
        var self = this;
        if (typeof RestClient === 'undefined') {
            this.renderError('Unable to load menu items right now.');
            return;
        }

        RestClient.get('menu-items', function(data) {
            self.state.items = Array.isArray(data) ? data : [];
            self.render();
        }, function() {
            self.state.items = [];
            self.renderError('Unable to load menu items right now.');
        });
    },

    groupByCategory: function() {
        var catNameMap = {};
        this.state.categories.forEach(function(c) {
            var id = c.category_id || c.id || c.categoryId;
            if (id === undefined || id === null) {
                return;
            }
            var name = c.name || c.category_name || 'Category ' + id;
            catNameMap[id] = name;
        });

        var groups = [];
        var groupById = {};

        this.state.items.forEach(function(item) {
            var cid = item.category_id || item.categoryId || item.category;
            var group = groupById[cid];
            if (!group) {
                group = {
                    id: cid,
                    name: catNameMap[cid] || (cid ? 'Category ' + cid : 'Other'),
                    items: []
                };
                groupById[cid] = group;
                groups.push(group);
            }
            group.items.push(item);
        });

        return groups;
    },

    render: function() {
        var container = this.state.container;
        var groups = this.groupByCategory();

        if (!groups.length) {
            this.renderEmpty();
            return;
        }

        var html = groups.map(function(group) {
            var itemsHtml = group.items.map(function(item) {
                var name = item.name || 'Menu item';
                var description = item.description || '';
                var price = item.price !== undefined && item.price !== null ? item.price : '';
                var img = item.image_url || item.imageUrl || '';
                var imgBlock = img ? '<img src="' + img + '" alt="' + name + '" class="menu-img">' : '';
                var priceBlock = price !== '' ? '<span class="menu-price">€' + price + '</span>' : '';

                return `
                    <div class="menu-item">
                        ${imgBlock}
                        <div class="menu-text">
                            <h4>${name}</h4>
                            <p>${description}</p>
                        </div>
                        ${priceBlock}
                    </div>
                `;
            }).join('');

            return `
                <div class="col-md-10 mb-5">
                    <h2 class="menu-category">${group.name}</h2>
                    ${itemsHtml}
                </div>
            `;
        }).join('');

        container.innerHTML = html;
    },

    renderLoading: function(message) {
        if (this.state.loading) {
            this.state.loading.innerHTML = '<p>' + message + '</p>';
        } else if (this.state.container) {
            this.state.container.innerHTML = '<div class="col-12 text-center"><p>' + message + '</p></div>';
        }
    },

    renderEmpty: function() {
        this.state.container.innerHTML = '<div class="col-12 text-center"><p>No menu items available.</p></div>';
    },

    renderError: function(message) {
        this.state.container.innerHTML = '<div class="col-12 text-center"><p>' + message + '</p></div>';
    }
};

window.MenuItemsService = MenuItemsService;