@props([
    'name',
    'id' => null,
    'selectedActivityName' => null,
    'placeholder' => '-PILIH NAMA KEGIATAN-',
])

@php
    $id = $id ?? $name;
@endphp

<select name="{{ $name }}" id="{{ $id }}" class="select-activity-name-live" data-placeholder="{{ $placeholder }}" {{ $attributes }}>
    @if ($selectedActivityName)
        <option value="{{ $selectedActivityName->id }}"
                data-start="{{ $selectedActivityName->start_date }}"
                data-end="{{ $selectedActivityName->end_date }}"
                data-year="{{ $selectedActivityName->year }}"
                data-quota="{{ $selectedActivityName->quota }}"
                selected>
            {{ $selectedActivityName->name }}
        </option>
    @else
        <option value="" data-start="" data-end="" data-year="" data-quota="">{{ $placeholder }}</option>
    @endif
</select>

@pushOnce('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
@endPushOnce

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.select-activity-name-live').forEach(function(el) {
                if (el.tomselect) return; // Prevent double initialization

                const initialOptions = [];
                Array.from(el.options).forEach(opt => {
                    if (opt.value) {
                        initialOptions.push({
                            id: opt.value,
                            name: opt.text,
                            start_date: opt.getAttribute('data-start') || '',
                            end_date: opt.getAttribute('data-end') || '',
                            year: opt.getAttribute('data-year') || '',
                            quota: opt.getAttribute('data-quota') || ''
                        });
                    }
                });

                const placeholder = el.getAttribute('data-placeholder') || '-PILIH NAMA KEGIATAN-';
                const includeId = el.options[el.selectedIndex]?.value || '';

                const ts = new TomSelect(el, {
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name'],
                    options: initialOptions,
                    items: initialOptions.map(o => o.id),
                    placeholder: placeholder,
                    load: function(query, callback) {
                        const yearFilterEl = document.getElementById('year_filter');
                        const year = yearFilterEl ? yearFilterEl.value : '';
                        
                        // If year is not selected, we don't load activity names (keeps it empty)
                        if (!year) {
                            return callback();
                        }

                        var url = '/dictionaries/activity-names/search?q=' + encodeURIComponent(query) + '&year=' + encodeURIComponent(year);
                        if (includeId) {
                            url += '&include_id=' + encodeURIComponent(includeId);
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
                                ${item.year ? `<span style="font-size: 0.8em; color: #888; margin-left: 5px;">(${escape(item.year)})</span>` : ''}
                            </div>`;
                        },
                        item: function(item, escape) {
                            return `<div>${escape(item.name)}</div>`;
                        }
                    }
                });

                // Listen to year_filter changes to clear options and selections
                const yearFilterEl = document.getElementById('year_filter');
                if (yearFilterEl) {
                    yearFilterEl.addEventListener('change', function() {
                        ts.clear();
                        ts.clearOptions();
                    });
                }

                // Trigger a custom event when selected item changes so parent page can listen
                ts.on('change', function(value) {
                    const selectedOpt = ts.options[value];
                    const event = new CustomEvent('activity-name-selected', {
                        detail: {
                            value: value,
                            activityName: selectedOpt
                        }
                    });
                    el.dispatchEvent(event);
                });
            });
        });
    </script>
@endPushOnce
