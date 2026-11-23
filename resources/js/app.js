const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if ('IntersectionObserver' in window && !reducedMotion) {
    const revealObserver = new IntersectionObserver((items, observer) => {
        items.forEach(item => {
            if (item.isIntersecting) {
                item.target.classList.add('is-visible');
                observer.unobserve(item.target);
            }
        });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(element => revealObserver.observe(element));
    document.documentElement.classList.add('motion-ready');
}

const menuToggle = document.querySelector('.mobile-menu-toggle');
const mobileNav = document.querySelector('#mobile-nav');
menuToggle?.addEventListener('click', () => {
    const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
    menuToggle.setAttribute('aria-expanded', String(!expanded));
    mobileNav.hidden = expanded;
});
mobileNav?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
    mobileNav.hidden = true;
    menuToggle.setAttribute('aria-expanded', 'false');
}));

document.querySelectorAll('[data-filter]').forEach(button => button.addEventListener('click', () => {
    document.querySelectorAll('[data-filter]').forEach(filter => {
        const active = filter === button;
        filter.classList.toggle('active', active);
        filter.setAttribute('aria-pressed', String(active));
    });
    document.querySelectorAll('[data-category]').forEach(card => {
        card.hidden = button.dataset.filter !== 'all' && card.dataset.category !== button.dataset.filter;
        if (!card.hidden) card.classList.add('is-visible');
    });
}));

const tabs = [...document.querySelectorAll('[data-tab]')];
function selectTab(tab) {
    tabs.forEach(button => {
        const active = button === tab;
        button.classList.toggle('active', active);
        button.setAttribute('aria-selected', String(active));
        button.tabIndex = active ? 0 : -1;
    });
    document.querySelectorAll('[data-tab-panel]').forEach(panel => {
        panel.hidden = panel.dataset.tabPanel !== tab.dataset.tab;
    });
}
tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => selectTab(tab));
    tab.addEventListener('keydown', event => {
        let next;
        if (event.key === 'ArrowRight') next = (index + 1) % tabs.length;
        if (event.key === 'ArrowLeft') next = (index - 1 + tabs.length) % tabs.length;
        if (event.key === 'Home') next = 0;
        if (event.key === 'End') next = tabs.length - 1;
        if (next !== undefined) {
            event.preventDefault();
            selectTab(tabs[next]);
            tabs[next].focus();
        }
    });
});

document.querySelectorAll('[data-project-open]').forEach(button => button.addEventListener('click', () => {
    document.getElementById(button.dataset.projectOpen)?.showModal();
}));
document.querySelectorAll('[data-dialog-close]').forEach(button => button.addEventListener('click', () => button.closest('dialog')?.close()));
document.querySelectorAll('dialog').forEach(dialog => dialog.addEventListener('click', event => {
    if (event.target !== dialog) return;
    const bounds = dialog.getBoundingClientRect();
    if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) dialog.close();
}));

document.querySelectorAll('[data-password-toggle]').forEach(button => button.addEventListener('click', () => {
    const input = document.getElementById(button.dataset.passwordToggle);
    const visible = input.type === 'password';
    input.type = visible ? 'text' : 'password';
    button.setAttribute('aria-pressed', String(visible));
    button.setAttribute('aria-label', visible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
}));
document.querySelector('[data-alert-close]')?.addEventListener('click', event => event.currentTarget.closest('.alert').remove());

const entryData = document.querySelector('#entries-data');
if (entryData) {
    const entries = JSON.parse(entryData.textContent);
    const dialog = document.querySelector('#entry-dialog');
    const form = document.querySelector('#entry-form');
    const methodInput = document.querySelector('#entry-method');
    const entryId = document.querySelector('#entry-id');
    const kindInput = document.querySelector('#entry-kind');
    const title = document.querySelector('[data-entry-dialog-title]');
    const preview = document.querySelector('#entry-image-preview');
    const removeImageLabel = document.querySelector('#remove-image-label');
    const fill = (entry) => {
        ['title', 'subtitle', 'period', 'category', 'description', 'url', 'sort_order'].forEach(field => {
            document.getElementById('entry-' + field).value = entry[field] ?? '';
        });
        document.querySelector('#entry-is_published').value = entry.is_published ? '1' : '0';
    };
    document.querySelectorAll('[data-entry-create]').forEach(button => button.addEventListener('click', () => {
        form.reset();
        methodInput.value = 'POST';
        entryId.value = '';
        form.action = form.dataset.storeUrl;
        fill({sort_order: Math.max(0, ...entries.map(entry => entry.sort_order)) + 1, is_published: true});
        preview.hidden = true;
        preview.removeAttribute('src');
        removeImageLabel.hidden = true;
        title.textContent = 'Tambah data baru';
        dialog.showModal();
    }));
    document.querySelectorAll('[data-entry-edit]').forEach(button => button.addEventListener('click', () => {
        const entry = entries.find(item => String(item.id) === button.dataset.entryEdit);
        if (!entry) return;
        form.reset();
        methodInput.value = 'PUT';
        entryId.value = entry.id;
        kindInput.value = entry.kind;
        form.action = form.dataset.storeUrl + '/' + entry.id;
        fill(entry);
        preview.hidden = !entry.image_path;
        if (entry.image_path) preview.src = '/media/' + entry.image_path;
        else preview.removeAttribute('src');
        removeImageLabel.hidden = !entry.image_path;
        title.textContent = 'Edit data';
        dialog.showModal();
    }));
    if (dialog.dataset.oldInput === 'true') {
        if (entryId.value && methodInput.value === 'PUT') form.action = form.dataset.storeUrl + '/' + encodeURIComponent(entryId.value);
        dialog.showModal();
    }
    document.querySelectorAll('[data-entry-delete]').forEach(button => button.addEventListener('click', () => {
        const deleteForm = document.querySelector('#delete-form');
        deleteForm.action = deleteForm.dataset.baseUrl + '/' + button.dataset.entryDelete;
        document.querySelector('[data-delete-title]').textContent = button.dataset.entryTitle;
        document.querySelector('#delete-dialog').showModal();
    }));
    const search = document.querySelector('[data-entry-search]');
    search?.addEventListener('input', () => {
        const query = search.value.toLocaleLowerCase('id').trim();
        let count = 0;
        document.querySelectorAll('[data-entry-row]').forEach(row => {
            row.hidden = !row.dataset.search.includes(query);
            if (!row.hidden) count++;
        });
        document.querySelector('.search-empty').hidden = count !== 0 || entries.filter(entry => entry.kind === kindInput.value).length === 0;
        document.querySelector('[data-search-count]').textContent = count + ' item';
    });
}

document.querySelectorAll('[data-file-preview]').forEach(input => {
    let objectUrl;
    input.addEventListener('change', () => {
        const preview = document.getElementById(input.dataset.filePreview);
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        const file = input.files[0];
        if (!file || !['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            preview.hidden = true;
            return;
        }
        objectUrl = URL.createObjectURL(file);
        preview.src = objectUrl;
        preview.hidden = false;
    });
});

document.querySelectorAll('form').forEach(form => form.addEventListener('submit', () => {
    const submit = form.querySelector('button[type="submit"]');
    if (submit && form.checkValidity()) {
        submit.disabled = true;
        submit.setAttribute('aria-busy', 'true');
    }
}));

