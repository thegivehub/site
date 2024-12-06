(function() {
    // Helper functions for DOM selection
    const $ = str => document.querySelector(str);
    const $$ = str => document.querySelectorAll(str);

    // Main application object
    const app = {
        data: {
            posts: [],
            categories: [],
            currentFilter: 'all',
            posts: [],
            categories: [
                {
                    "name": "Charity",
                    "id": "8",
                    "language": "en",
                    "icon": "/img/charity.svg",
                    "post_count": "19"
                },
                {
                    "name": "Climate & Environment",
                    "id": "5",
                    "language": "en",
                    "icon": "/img/climate.svg",
                    "post_count": "4"
                },
                {
                    "name": "Community Development",
                    "id": "1",
                    "language": "en",
                    "icon": "/img/community.svg",
                    "post_count": "1"
                },
                {
                    "name": "Economic Empowerment",
                    "id": "6",
                    "language": "en",
                    "icon": "/img/economic.svg",
                    "post_count": "3"
                },
                {
                    "name": "Education & Skills",
                    "id": "2",
                    "language": "en",
                    "icon": "/img/education.svg",
                    "post_count": "3"
                },
                {
                    "name": "Health & Wellbeing",
                    "id": "4",
                    "language": "en",
                    "icon": "/img/health.svg",
                    "post_count": "2"
                },
                {
                    "name": "Infrastructure",
                    "id": "7",
                    "language": "en",
                    "icon": "/img/infrastructure.svg",
                    "post_count": "2"
                },
                {
                    "name": "Water & Sanitation",
                    "id": "3",
                    "language": "en",
                    "icon": "/img/water.svg",
                    "post_count": "4"
                }
                ]
        },
        
        state: {
            loaded: false,
            editing: false,
            currentPostId: null,
            saving: false,
            sortKey: "published_at",
            sortOrd: -1
        },

        async init() {
            // Initialize the application
            //app.bindEvents();
            await app.initCategories();
            await app.fetchPosts();
            app.themeToggle.init();
            app.state.loaded = true;
        },

        bindEvents() {
            // Bind event listeners
            $('.search-box input').addEventListener('keyup', (e) => app.searchPosts(e.target.value));
            $$('.nav-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    app.setActiveNavItem(e.target);
                    app.filterPosts(e.target.dataset.filter);
                });
            });
        },
        
        async initCategories() {
            let resp = await fetch("api/categories?lang=en&x=getCategories");
            app.data.categories = await resp.json();
            
            //app.updateCategoryPostCounts();
            app.renderCategories();
            app.bindEvents();
        },
        
        fetch(url, callback) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    app.data = data;
                    app.state.loaded = true;
                    if (callback && typeof(callback) === "function") {
                        callback(data);
                    }
                })
                .catch(error => console.error('Fetch error:', error));
        },

        async fetchPosts() {
            let resp = await fetch("api/posts?language=en");
            let posts = await resp.json();

            app.data.posts = posts;
            app.renderPosts(app.data.posts);

            return posts;
        },

        changeSort() {
            app.state.sortKey = $("#sort-by").value;
            app.state.sortOrd = $("input[name='sort-dir']:checked").value;
            $("#sort-by option[selected]").removeAttribute("selected");
            $(`#sort-by option[value='app.state.sortKey']`).addAttribute("selected");
            document.querySelector("header .filters div.post-actions > details")?.removeAttribute("open");
            app.sortPosts(app.data.posts);
            app.renderPosts(app.data.posts);
        },

        sortPosts(postArr) {
            postArr.sort((a, b) => {
                let out = 0;
                if (a[app.state.sortKey] < b[app.state.sortKey]) {
                    out = -1;
                } else if (a[app.state.sortKey] > b[app.state.sortKey]) {
                    out = 1;
                }
                return out * app.state.sortOrd;
            });
            return postArr;
        },

        renderPosts(postsToRender = app.data.posts) {
            const postsList = $('#posts-list');
            
            postsToRender = app.sortPosts(postsToRender);

            const postsHTML = postsToRender.map(post => app.createPostHTML(post)).join('');
            postsList.innerHTML = postsHTML;
        },

        createPostHTML(post) {
            if (post.published_at) {
                post.published_at = new Intl.DateTimeFormat("en-US", { weekday: "short", month: "short", day: "numeric", year: "numeric" }).format(new Date(post.published_at));
            }
            return `
                <div class="post-item" data-id="${post.id}">
                    <img src="${post.post_image}" alt="${post.title}" class="post-image">
                    <div class="post-details">
                        <h3 class="post-title">${post.title}</h3>
                        <p class="post-meta">
                            <span><i class="fas fa-user"></i> ${post.user || 'Chris Robison'}</span>
                            <span><i class="fas fa-calendar"></i> ${post.published_at}</span>
                            <span><i class="fas fa-folder"></i> ${app.data.categories[post.category_id - 1].name}</span>
                            <span class="status-badge ${post.status === 'published' ? 'status-published' : 'status-draft'}">
                                <i class="fas fa-${post.status === 'published' ? 'check-circle' : 'clock'}"></i>
                                ${post.status.charAt(0).toUpperCase() + post.status.slice(1)}
                            </span>
                        </p>
                        <div class="post-actions">
                            <span>
                            <button class="btn btn-outline" onclick="app.editPost(${post.id})">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="btn btn-outline" onclick="app.previewPost(${post.id})">
                                <i class="fas fa-eye"></i>
                                Preview 
                            </button>
                            </span>
<button class="btn btn-danger" onclick="app.deletePost(${post.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        },

        searchPosts(term) {
            const filteredPosts = app.data.posts.filter(post => 
                post.title.toLowerCase().includes(term.toLowerCase()) ||
                post.subtitle.toLowerCase().includes(term.toLowerCase()) ||
                post.category.toLowerCase().includes(term.toLowerCase())
            );
            app.renderPosts(filteredPosts);
        },

        filterPosts(filter = 'all') {
            app.data.currentFilter = filter;
            let filteredPosts = app.data.posts;
            
            switch(filter) {
                case 'published':
                    filteredPosts = app.data.posts.filter(post => post.status === 'published');
                    break;
                case 'draft':
                    filteredPosts = app.data.posts.filter(post => post.status === 'draft');
                    break;
            }
            
            app.renderPosts(filteredPosts);
        },

        setActiveNavItem(element) {
            $$('.nav-link,.category-item').forEach(link => link.classList.remove('active'));
            element.classList.add('active');
        },

        deletePost(id) {
            if (confirm('Are you sure you want to delete this post?')) {
                app.data.posts = app.data.posts.filter(post => post.id !== id);
                app.renderPosts();
            }
        },

        editPost(id) {
            app.state.editing = true;
            app.state.currentPostId = id;
            const post = app.data.posts.find(post => post.id == id);
            app.state.currentPost = post;

            if (post) {
                app.openEditor(post);
            }
        },

        openEditor(post = null) {
            const modal = $('#editor-modal');
            const form = $('#post-form');
            const editorTitle = $('#editor-title span');
            
            // Reset form
            form.reset();
            
            
            if (post) {
                editorTitle.textContent = 'Edit Post';
                app.state.currentPostId = post.id;
                
                // Fill form with post data
                $('#post-title').value = post.title;
                $('#post-subtitle').value = post.subtitle;
                $('#post-category_id').value = post.category_id;
                $('#post-status').value = post.status;
                $('#post-markdown').value = post.markdown;
                $('#post-content').value = post.content;
                $('#post-post_image').value = post.post_image;
                $('#post-published_at').value = post.published_at;
                $('#post-created_at').value = post.created_at;
                $('#post-slug').value = post.slug;
                
                if (post.post_image) {
                    $('#image-preview').style.backgroundImage = `url(${post.post_image})`;
                    $('#image-preview').innerHTML = '';
                }
            } else {
                editorTitle.textContent = 'New Post';
                app.state.currentPostId = null;
                $('#image-preview').style.backgroundImage = 'none';
                $('#post-content').value = '';
                $('#post-markdown').value = '';
                $('#image-preview').innerHTML = `
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Click to upload image</span>
                `;
            }
            
            if (app.editor) {
                app.editor.toTextArea();
            }
            app.initEditor();

            if (post) {
                app.editor.value(post.markdown || '');
            }

            modal.style.display = 'block';
        },

        closeEditor() {
            $('#editor-modal').style.display = 'none';
            app.state.editing = false;
            app.state.currentPostId = null;
        },

        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = $('#image-preview');
                    preview.style.backgroundImage = `url(${e.target.result})`;
                    preview.innerHTML = '';
                };
                reader.readAsDataURL(file);
            }
        },
        async postData(url = "", data = {}) {
            const response = await fetch(url, {
                method: "POST", // *GET, POST, PUT, DELETE, etc.
                mode: "no-cors", // no-cors, *cors, same-origin
                cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                credentials: "same-origin", // include, *same-origin, omit
                headers: {
                    "Content-Type": "application/json",
                   // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                redirect: "follow", // manual, *follow, error
                referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                body: JSON.stringify(data), // body data type must match "Content-Type" header
            });
            return response.json(); // parses JSON response into na
        },
 
        async savePost(event) {
            event.preventDefault();
            if (app.state.saving) return;

            const form = event.target;
            
            let out = {};
            $$("form input, form select, form textarea").forEach(item=>{ if (item.name && item.value) out[item.name] = item.value });
            
            let converter = new showdown.Converter();
            out.content = converter.makeHtml(out.markdown);
            out.category = app.data.categories[out.category_id].name;
            out.slug = out.slug.replace(/[^a-zA-Z0-9_\-]/g, '-');

            console.dir(out);

            app.state.saving = true;

            try {
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.classList.add('loading');

                const endpoint = app.state.currentPostId 
                    ? `${API_ENDPOINT}/${app.state.currentPostId}?x=update`
                    : API_ENDPOINT;

                let response = await app.postData(endpoint, out);

                // Update local data
                if (app.state.currentPostId) {
                    const index = app.data.posts.findIndex(p => p.id === app.state.currentPostId);
                    if (index !== -1) {
                        app.data.posts[index] = out;
                    }
                } else {
                    app.data.posts.unshift(out);
                }

                // Refresh display
                app.renderPosts();
                app.closeEditor();

                // Show success message
                alert('Post saved successfully!');

            } catch (error) {
                console.error('Save error:', error);
                alert('Failed to save post. Please try again.');
            } finally {
                app.state.saving = false;
                form.querySelector('button[type="submit"]').classList.remove('loading');
            }
        },
        show(view) {
            switch(view) {
                case "categories": 
                    app.openCategoryEditor();
                    break;
                case "all":
                    app.filterPosts("all");
                    break;
                case "draft":
                case "drafts":
                    app.filterPosts("drafts");
                    break;

            }
        },
        display(data, tgt) {
            let out = "<table><thead><tr>";
            const keys = Object.keys(data[0]);
            if (keys) {
                keys.forEach(key => {
                    out += `<th>${key}</th>`;
                });
            }
            out += "</tr></thead><tbody>";
            data.forEach(item => {
                out += `<tr>`;
                keys.forEach(key => {
                    out += `<td>${item[key]}</td>`;
                });
                out += `</tr>`;
            });
            out += "</tbody></table>";
            if (tgt) {
                tgt.innerHTML = out;
            }
            return out;
        },
        previewPost(id) {
            let post;
            if (id) {
                post = app.data.posts.find(post => post.id == id);
            }
            let title, subtitle, markdown, content, slug, category, imagePreview, imageUrl;
            
            let converter = new showdown.Converter();

            if (post) {
                title =  post.title;
                subtitle = post.subtitle;
                markdown = post.markdown;
                content = converter.makeHtml(post.markdown);
                slug = post.slug;
                category = post.category;
                imagePreview = $('#image-preview').style.backgroundImage;
                imageUrl = post.post_image;
                imagePreview = imageUrl;
            } else {
                title = $('#post-title').value;
                subtitle = $('#post-subtitle').value;
                slug = $('#post-slug').value;
                markdown = $('#post-markdown').value;
                content = converter.makeHtml(markdown);
                category = $('#post-category').value;
                imagePreview = $('#image-preview').style.backgroundImage;
                imageUrl = imagePreview.replace('url("', '').replace('")', '');
            }

            // Format the current date
            const currentDate = new Date().toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Get the preview area and add the formatted HTML
            const previewArea = $('#preview-area');
            previewArea.innerHTML = `
                <article class="blog-post">
                    <header class="post-header">
                        <h1 class="post-title">${title || 'Untitled Post'}</h1>
                        ${subtitle ? `<h2 class="post-subtitle">${subtitle}</h2>` : ''}
                        <div class="post-meta">
                            <span><i class="fas fa-calendar"></i> ${currentDate}</span>
                            <span><i class="fas fa-folder"></i> ${category || 'Uncategorized'}</span>
                        </div>
                    </header>

                    ${imagePreview !== 'none' ? `
                        <img src="${imageUrl}" alt="${title}" class="featured-image">
                    ` : ''}

                    <div class="post-content">
                        ${app.formatContent(content)}
                    </div>
                </article>
            `;

            // Show the preview modal
            $('#preview-modal').style.display = 'block';
        },

        closePreview() {
            $('#preview-modal').style.display = 'none';
        },

        formatContent(content) {
            if (!content) return '';

            // Basic Markdown-like formatting
            return content
                // Convert line breaks to paragraphs
                .split('\n\n')
                .map(paragraph => paragraph.trim())
                .filter(paragraph => paragraph)
                .map(paragraph => `<p>${paragraph}</p>`)
                .join('')
                // Bold text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                // Italic text
                .replace(/\*(.*?)\*/g, '<em>$1</em>');
        },
        initEditor() {
            app.editor = new SimpleMDE({
                element: $('#post-markdown'),
                spellChecker: true,
                autosave: {
                    enabled: true,
                    unique_id: 'blog_post_markdown',
                    delay: 1000,
                },
                toolbar: [
                    'bold', 'italic', 'heading', '|',
                    'quote', 'unordered-list', 'ordered-list', '|',
                    'link', 'image', '|',
                    'preview', 'side-by-side', 'fullscreen', '|',
                    'guide'
                ],
                placeholder: 'Write your post content markdown here...',
                status: ['lines', 'words', 'cursor'],
                renderingConfig: {
                    singleLineBreaks: false,
                    codeSyntaxHighlighting: true,
                }
            });

            // Custom keyboard shortcuts
            app.editor.codemirror.setOption('extraKeys', {
                'Cmd-S': function(cm) {
                    app.savePost();
                },
                'Ctrl-S': function(cm) {
                    app.savePost();
                },
                'Esc': function(cm) {
                    if (cm.getOption('fullScreen')) {
                        cm.setOption('fullScreen', false);
                    }
                }
            });
        },

        toggleEditorHelp() {
            $('#markdown-help-modal').style.display = 'block';
        },

        closeMarkdownHelp() {
            $('#markdown-help-modal').style.display = 'none';
        },
        
        updateCategoryPostCounts() {
            for (let i=0; i < app.data.categories.length; i++) {

                let category = app.data.categories[i];
                app.data.categories[i].postCount = app.data.posts.filter(post => 
                    post.category_id == category.id &&
                    post.language === "en"
                ).length;
            }
        },

        renderCategories() {
            const container = document.getElementById('categories-container');
            const categoriesHTML = this.data.categories.map(category => {
                console.dir(category);
                return `
                <div class="category-item" data-categoryid="${category.id}">
                    <a href="#" class="category-link" onclick="app.filterByCategory('${category.id}', event);return false;">
                        <img src="${category.icon}" class="category-icon">
                        ${category.name}
                        <span class="post-count">(${category.post_count})</span>
                    </a>
                    <div class="category-actions">
                        <button class="btn btn-sm" onclick="app.editCategory(${category.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm" onclick="app.deleteCategory(${category.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                `}).join('');
            $("#categories-container").innerHTML = categoriesHTML;
        },

        filterByCategory(category_id) {
            const filteredPosts = this.data.posts.filter(post => 
                post.category_id === category_id
            );
            this.renderPosts(filteredPosts);
            
            // Update active state
            document.querySelectorAll('.category-item,.nav-link').forEach(link => 
                link.classList.remove('active')
            );
            const categoryLink = document.querySelector(`.category-item[data-categoryid="${category_id}"]`);
            if (categoryLink) categoryLink.classList.add('active');
        },

        openCategoryEditor(categoryId = null) {
            const category = categoryId ? 
                this.data.categories.find(c => c.id === categoryId) : 
                null;
            
            const modalHTML = `
                <div class="modal-content" style="max-width: 500px;">
                    <div class="modal-header">
                        <h2>
                            <i class="fas fa-folder"></i>
                            ${category ? 'Edit Category' : 'New Category'}
                        </h2>
                        <button class="close-btn" onclick="app.closeCategoryEditor()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form id="category-form" onsubmit="app.saveCategory(event)">
                        <div class="form-group">
                            <label for="category-name">Category Name</label>
                            <input type="text" 
                                   id="category-name" 
                                   name="name" 
                                   required 
                                   value="${category ? category.name : ''}">
                        </div>
                        <input type="hidden" id="category-id" value="${category ? category.id : ''}">
                        <div class="form-actions">
                            <button type="button" class="btn btn-outline" onclick="app.closeCategoryEditor()">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                ${category ? 'Update' : 'Create'} Category
                            </button>
                        </div>
                    </form>
                </div>
            `;

            const modal = document.createElement('div');
            modal.id = 'category-modal';
            modal.className = 'modal';
            modal.innerHTML = modalHTML;
            document.body.appendChild(modal);
            modal.style.display = 'block';
        },

        closeCategoryEditor() {
            const modal = document.getElementById('category-modal');
            if (modal) {
                modal.remove();
            }
        },

        saveCategory(event) {
            event.preventDefault();
            const form = event.target;
            const name = form.querySelector('#category-name').value;
            const id = form.querySelector('#category-id').value;
            
            if (id) {
                // Update existing category
                const index = this.data.categories.findIndex(c => c.id === parseInt(id));
                if (index !== -1) {
                    this.data.categories[index].name = name;
                    this.data.categories[index].slug = name.toLowerCase().replace(/\s+/g, '-');
                }
            } else {
                // Create new category
                const newCategory = {
                    id: Date.now(),
                    name: name,
                    slug: name.toLowerCase().replace(/\s+/g, '-'),
                    postCount: 0
                };
                this.data.categories.push(newCategory);
            }
            
            //this.updateCategoryPostCounts();
            this.renderCategories();
            this.closeCategoryEditor();
        },

        deleteCategory(categoryId) {
            if (!confirm('Are you sure you want to delete this category?')) {
                return;
            }
            
            // Remove category
            this.data.categories = this.data.categories.filter(c => c.id !== categoryId);
            
            // Update posts with this category to 'Uncategorized'
            this.data.posts.forEach(post => {
                if (post.category === categoryName) {
                    post.category = 'Uncategorized';
                }
            });
            
            //this.updateCategoryPostCounts();
            this.renderCategories();
            this.renderPosts();
        },

        editCategory(categoryId) {
            this.openCategoryEditor(categoryId);
        },

        upslug(val) {
            $("#post-slug").value = val.replace(/[\W]/g, '-').toLowerCase();

        },
        themeToggle: {
            init() {
                // Add theme toggle button to header
                const headerActions = document.querySelector('.header-content .post-actions');
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'theme-toggle';
                toggleBtn.innerHTML = `
                    <i class="fas fa-moon"></i>
                `;
                toggleBtn.onclick = this.toggleTheme.bind(this);
                headerActions.appendChild(toggleBtn);
        
                // Initialize theme from localStorage
                const savedTheme = localStorage.getItem('theme') || 'light';
                this.setTheme(savedTheme);
                
                // Watch for system theme changes
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                mediaQuery.addEventListener('change', e => {
                    if (!localStorage.getItem('theme')) {
                        this.setTheme(e.matches ? 'dark' : 'light');
                    }
                });
            },
        
            toggleTheme() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                this.setTheme(newTheme);
            },
        
            setTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                
                // Update toggle button icon
                const toggleBtn = document.querySelector('.theme-toggle');
                if (toggleBtn) {
                    toggleBtn.innerHTML = `
                        <i class="fas fa-${theme === 'dark' ? 'sun' : 'moon'}"></i>
                    `;
                }
        
                // Update SimpleMDE if it exists
                if (app.editor) {
                    if (theme === 'dark') {
                        app.editor.codemirror.setOption('theme', 'monokai');
                    } else {
                        app.editor.codemirror.setOption('theme', 'default');
                    }
                }
            }
        }


    };

    // Make app globally available and initialize
    window.app = app;
    window.API_ENDPOINT = '/site/admin/api/posts';

    app.init();
})();

