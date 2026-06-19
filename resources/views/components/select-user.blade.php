@props([
    'name',
    'id' => null,
    'selectedUser' => null,
    'placeholder' => '-PILIH PEGAWAI-',
    'excludeRoles' => false,
])

@php
    $id = $id ?? $name;
@endphp

<select name="{{ $name }}" id="{{ $id }}" class="select-user-live" data-placeholder="{{ $placeholder }}" data-exclude-roles="{{ $excludeRoles ? 'true' : 'false' }}" {{ $attributes }}>
    @if ($selectedUser)
        <option value="{{ $selectedUser->id }}" data-phone="{{ $selectedUser->phone_number ?? '-' }}" selected>
            {{ $selectedUser->name }}
        </option>
    @else
        <option value="" data-phone="">{{ $placeholder }}</option>
    @endif
</select>

@pushOnce('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
@endPushOnce

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.select-user-live').forEach(function(el) {
                if (el.tomselect) return; // Prevent double initialization

                const initialOptions = [];
                Array.from(el.options).forEach(opt => {
                    if (opt.value) {
                        initialOptions.push({
                            id: opt.value,
                            name: opt.text,
                            phone_number: opt.getAttribute('data-phone') || '-'
                        });
                    }
                });

                const excludeRoles = el.getAttribute('data-exclude-roles') === 'true';
                const placeholder = el.getAttribute('data-placeholder') || '-PILIH PEGAWAI-';

                const ts = new TomSelect(el, {
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name', 'employee_id'],
                    options: initialOptions,
                    items: initialOptions.map(o => o.id),
                    placeholder: placeholder,
                    load: function(query, callback) {
                        if (query.length < 3) return callback();
                        var url = '/users/search?q=' + encodeURIComponent(query);
                        if (excludeRoles) {
                            url += '&exclude_roles=true';
                        }
                        fetch(url)
                            .then(response => response.json())
                            .then(json => {
                                callback(json);
                            }).catch(() => {
                                callback();
                            });
                    },
                    render: {
                        option: function(item, escape) {
                            return `<div>
                                <span class="font-semibold">${escape(item.name)}</span>
                                ${item.employee_id ? `<span style="font-size: 0.8em; color: #888; margin-left: 5px;">(${escape(item.employee_id)})</span>` : ''}
                            </div>`;
                        },
                        item: function(item, escape) {
                            return `<div>${escape(item.name)}</div>`;
                        }
                    }
                });

                // Trigger a custom event when selected item changes so parent page can listen
                ts.on('change', function(value) {
                    const event = new CustomEvent('user-selected', {
                        detail: {
                            value: value,
                            user: ts.options[value]
                        }
                    });
                    el.dispatchEvent(event);
                });
            });
        });
    </script>
@endPushOnce
