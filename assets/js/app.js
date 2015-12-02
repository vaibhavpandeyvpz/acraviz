(function ($) {
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body',
        placement: 'bottom'
    });
    $.extend({
        growl: $.bootstrapGrowl
    });
    var $table = $('[data-role="datatable"]');
    if ($table.length === 1) {
        var columns = [],
            $actions = $('[data-action]');
        $table.find('thead th[data-key]').each(function (i, el) {
            var key = $(el).data('key');
            switch (key) {
                case 'crash_count':
                    columns.push({
                        data: key,
                        name: key,
                        render: function (data, type) {
                            if (type == 'display') {
                                return data == null ? 0 : data;
                            }
                            return data;
                        }
                    });
                    break;
                case 'exception':
                    columns.push({
                        data: key,
                        name: key,
                        render: function (data, type, row) {
                            if (type == 'display') {
                                return '<a class="link-danger" href="' + $table.data('view').replace('%d', row.id) + '">' + data + '</a>';
                            }
                            return data;
                        }
                    });
                    break;
                case 'id':
                    columns.push({
                        data: key,
                        name: key,
                        render: function (data, type) {
                            if (type == 'display') {
                                return '<input type="checkbox" name="row_id" value="' + data + '">';
                            }
                            return data;
                        },
                        searchable: false,
                        sortable: false
                    });
                    break;
                case 'package_name':
                    columns.push({
                        data: key,
                        name: key,
                        render: function (data, type, row) {
                            if (type == 'display') {
                                var str = '<strong>' + row.title + '</strong>';
                                if (typeof row.app_version_name !== 'undefined') {
                                    str += ' - ' + row.app_version_name + ' (' + row.app_version_code + ')';
                                }
                                str += '<br><span class="text-muted">' + data + '</span>';
                                return str;
                            }
                            return data;
                        }
                    });
                    break;
                case 'phone_model':
                    columns.push({
                        data: key,
                        name: key,
                        render: function (data, type, row) {
                            if (type == 'display') {
                                return '<strong>' + row.brand + ' ' + data + '</strong><br>Runs <span class="text-success">Android <strong>' + row.android_version + '</strong></span>';
                            }
                            return data;
                        }
                    });
                    break;
                default:
                    columns.push({
                        data: key,
                        name: key
                    });
                    break;
            }
        });
        $table.on('preXhr.dt', function () {
            $actions.each(function (i, el) {
                $(el).prop('disabled', true);
            });
        });
        $table.on('xhr.dt', function () {
            $actions.each(function (i, el) {
                $(el).prop('disabled', false);
            });
        });
        var $datatable = $table.DataTable({
            ajax: {
                type: 'POST',
                url: $table.data('url')
            },
            autoWidth: false,
            columns: columns,
            order: [[columns.length - 1, 'desc']],
            pagingType: 'full_numbers',
            processing: false,
            responsive: true,
            serverSide: true
        });
        $actions.each(function (i, el) {
            var $el = $(el),
                action = $el.data('action');
            $el.click(function () {
                if (action == 'refresh') {
                    $datatable.ajax.reload(null, false);
                } else if (action == 'deselect') {
                    $.each($table.find('tbody tr td > input[name="row_id"]:checked'), function (i, ele) {
                        $(ele).prop('checked', false);
                    });
                } else if (action == 'select') {
                    $.each($table.find('tbody tr td > input[name="row_id"]'), function (i, ele) {
                        $(ele).prop('checked', true);
                    });
                } else if (action.indexOf('delete:') === 0) {
                    var selected = [];
                    $.each($table.find('tbody tr td > input[name="row_id"]:checked'), function (i, ele) {
                        selected.push($(ele).val());
                    });
                    switch (action) {
                        case 'delete:application':
                            if (selected.length == 1) {
                                bootbox.confirm('Deleting selected applications will also delete associated crash reports. Continue?', function (result) {
                                    if (result) {
                                        $.post($el.data('url'), {
                                            row_ids: selected
                                        }).error(function () {
                                            $.growl('Unable to delete selected applications.', { type: 'danger' });
                                        }).success(function () {
                                            $.growl('Selected applications have been deleted.', { type: 'success' });
                                            $datatable.ajax.reload(null, false);
                                        });
                                    }
                                });
                            } else {
                                $.growl('Please select at least one application to delete.', { type: 'warning' });
                            }
                            break;
                        case 'delete:report':
                            if (selected.length == 1) {
                                bootbox.confirm('Are you sure you wish to delete selected crash reports? This cannot be undone.', function (result) {
                                    if (result) {
                                        $.post($el.data('url'), {
                                            row_ids: selected
                                        }).error(function () {
                                            $.growl('Unable to delete selected crash reports.', { type: 'danger' });
                                        }).success(function () {
                                            $.growl('Selected crash reports have been deleted.', { type: 'success' });
                                            $datatable.ajax.reload(null, false);
                                        });
                                    }
                                });
                            } else {
                                $.growl('Please select at least one crash report to delete.', { type: 'warning' });
                            }
                            break;
                        default:
                            break;
                    }
                }
            });
        });
    }
    var $searchviz = $('[data-role="searchviz"]');
    if ($searchviz.length === 1) {
        var $provider = new Bloodhound({
            datumTokenizer: function(d) {
                return Bloodhound.tokenizers.whitespace(d.url);
            },
            limit: 10,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: $searchviz.data('url'),
                wildcard: '%s'
            }
        });
        $provider.initialize();
        $searchviz.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            displayKey: 'exception',
            name: 'url',
            source: $provider.ttAdapter(),
            templates: {
                empty: [
                    '<div class="tt-suggestion">',
                    'Did not find any crash reports related to your query',
                    '</div>'
                ].join('\n'),
                footer: Handlebars.compile('<div class="text-center">Found <strong>{{suggestions.length}}</strong> results for <code>{{query}}</code><div>'),
                suggestion: Handlebars.compile([
                    '<div>',
                    '<span class="text-danger">{{exception}}</span><br>',
                    '<small><span class="text-success">{{application}}</span> - {{datetime}}</small>',
                    '</div>'
                ].join('\n'))
            },
            valueKey: 'url'
        }).on('typeahead:selected', function(event, datum) {
            window.location.href = datum.url;
        });
    }
})(jQuery);
