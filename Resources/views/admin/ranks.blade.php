<link rel="stylesheet" href="@PluginAssets('css/admin.css')">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>

<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="nestable-pos"><i class="fas fa-list"></i> {{ __('forum_arckene::admin.Ranks.title') }}</h6>
            </div>
            <div class="card-body">
                <a onclick="addRank($(this))" class="btn btn-primary btn-large btn-block" href="#">{{ __('forum_arckene::admin.Ranks.add_rank') }}</a>
                <div class="dd">
                    <ol class="dd-list">

                        @foreach($ranks as $r)
                            <li class="dd-item dd3-item" data-id="{{ $r['id'] }}">
                                <div class="dd-handle dd3-handle">
                                    <i class="fas fa-bars"></i>
                                </div>
                                <div class="dd3-content">
                                    <span class="py-2 d-inline-block" style="background: {{ $r['background'] }}; color: {{ $r['color'] }}; padding: 0 1rem; border-radius: 3px"><span class="mr-2">#{{ $r['id'] }}</span>





                                        {{ rankName($r['name']) }}

                                    </span>
                                    <div class="float-right">


                                        <button id="default" onclick="defaultRank({{ $r['id'] }})" class="btn btn-warning mr-2 @if($r['default'] == 1) disabled @endif" data-toggle="tooltip" data-placement="top" title="{{ __('forum_arckene::admin.Ranks.rank_default') }}" @if($r['default'] == 1) disabled @endif>
                                            <i class="fas fa-globe"></i>
                                        </button>

                                        <button id="edit" onclick="editRank(this, {{ $r['id'] }})" class="btn btn-primary mr-2">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button id="delete" onclick="xhrDelete(this, '{{ route('admin.forum.xhr_rank_delete', [$r['id']]) }}' , {{ $r['id'] }})" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list"></i> {{ __('forum_arckene::admin.Ranks.title_permissions') }}</h6>
            </div>
            <div class="card-body">
                <a onclick="xhrPermissions($(this))" class="btn btn-primary btn-large btn-block" href="#">{{ __('forum_arckene::admin.Ranks.regenerate_permissions') }}</a>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom de la permissions</th>
                        <th>Description de la permissions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $p)
                        <tr>
                            <td>{{ $p['id'] }}</td>
                            <td>{{ $p['name'] }}</td>
                            <td>{{ __('forum_arckene::permissions.' . $p['name']) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Nom de la permissions</th>
                        <th>Description de la permissions</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div ng-app>
    <div class="modal fade" tabindex="-1" role="dialog" id="addRank">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('forum_arckene::admin.Ranks.add_rank') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form__forum_rank">
                    <div class="modal-body">

                        <label for="name">{{ __('forum_arckene::admin.Ranks.rank_name') }}</label>
                        <input type="text" name="name" id="name" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Ranks.rank_name') }}" required>

                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox" class="custom-control-input" id="staff" name="staff">
                            <label class="custom-control-label" for="staff">Ce grade est il un grade staff ?</label>
                        </div>

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.1/angular.min.js"></script>
                        <label for="background">{{ __('forum_arckene::admin.Ranks.rank_background') }}</label>
                        <label class="color-picker mb-3" ng-init="background='#dddddd'; ">
                            <input class="color" type="color" id="background" ng-model="background">
                            <input class="hex" type="text" ng-model="background" name="background" onfocus="this.select()" readonly="">
                        </label>

                        <label for="color">{{ __('forum_arckene::admin.Ranks.rank_color') }}</label>
                        <label class="color-picker mb-3" ng-init="color='#dddddd'; ">
                            <input class="color" type="color" id="color" ng-model="color">
                            <input class="hex" type="text" ng-model="color" name="color" onfocus="this.select()" readonly="">
                        </label>

                        <div class="row">
                            @foreach($permissions as $p)
                                <div class="col-6">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="hidden" name="perms[{{$p['id']}}]" id="perms_{{$p['id']}}" value="0">
                                                <input type="checkbox" onclick="checkPerm(this, '#perms_{{$p['id']}}')">
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $p['name'] }}" readonly>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="submit__forum_rank" type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-paper-plane"></i>
                            </span>
                            <span class="text">
                                {{ __('forum_arckene::admin.add') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="editRank">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('forum_arckene::admin.Ranks.edit_rank') }}<span id="tit"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form__forum_edit_rank">
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id" required>

                        <label for="edit_name">{{ __('forum_arckene::admin.Ranks.rank_name') }}</label>
                        <input type="text" name="edit_name" id="edit_name" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Ranks.rank_name') }}" required>

                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox" class="custom-control-input" id="edit_staff" name="edit_staff">
                            <label class="custom-control-label" for="edit_staff">Ce grade est il un grade staff ?</label>
                        </div>

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.1/angular.min.js"></script>
                        <label for="edit_background">{{ __('forum_arckene::admin.Ranks.rank_background') }}</label>
                        <label class="color-picker mb-3" ng-init="edit_background='#dddddd'; ">
                            <input class="color" type="color" id="edit_background" ng-model="edit_background">
                            <input class="hex" type="text" ng-model="edit_background" name="edit_background" onfocus="this.select()" readonly="">
                        </label>


                        <label for="edit_color">{{ __('forum_arckene::admin.Ranks.rank_color') }}</label>
                        <label class="color-picker mb-3" ng-init="edit_color='#dddddd'; ">
                            <input class="color" type="color" id="edit_color" ng-model="edit_color">
                            <input class="hex" type="text" ng-model="edit_color" name="edit_color" onfocus="this.select()" readonly="">
                        </label>

                        <div class="row">

                            <div class="col-4 text-center">
                                <label>
                                    <input type="checkbox" disabled checked> {{ __('messages.YES') }}
                                </label>
                            </div>
                            <div class="col-4 text-center">
                                <label>
                                    <input type="checkbox" disabled id="indeterminate"> {{ __('messages.NO') }}
                                    <script>
                                        document.getElementById("indeterminate").indeterminate = true;
                                    </script>
                                </label>
                            </div>
                            <div class="col-4 text-center">
                                <label>
                                    <input type="checkbox" disabled> {{ __('forum_arckene::admin.Ranks.undefined') }}
                                </label>
                            </div>

                            @foreach($permissions as $p)
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="hidden" name="edit_perms[{{$p['id']}}]" id="edit_perms_{{$p['id']}}" value="0">
                                                <input type="checkbox" onclick="checkPerm(this, '#edit_perms_{{$p['id']}}')">
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $p['name'] }}" readonly>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="submit__forum_edit_rank" type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-paper-plane"></i>
                            </span>
                            <span class="text">
                                {{ __('forum_arckene::admin.save') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    function checkPerm(cb, elem) {
        if (cb.readOnly) {
            $(elem).val(0);
            cb.checked = cb.readOnly = false;
        } else if (!cb.checked) {
            $(elem).val(2);
            cb.readOnly = cb.indeterminate = true;
        } else {
            $(elem).val(1);
        }
    }
</script>

<script>
    function loadHeader(element) {
        if(element.hasClass('isLoading')) {
            element.html(element.data('html'));
        } else {
            element.attr('data-html', element.html());
            element.html("<i class=\"fas fa-spinner fa-spin\"></i> " + loading_sentence);
        }
        element.toggleClass("isLoading")
    }

    $('.dd').nestable({
        maxDepth: 1
    }).on('change',function(e){
        let json = JSON.stringify($('.dd').nestable('serialize'));
        loadHeader($('#nestable-pos'));
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.forum.ajax_ranks_nestable') }}',
            headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
            },
            data: 'json=' + json
        });
        setTimeout(function () {
            loadHeader($('#nestable-pos'));
        }, 500)
    });
</script>

<script>

    function addRank() {
        $('#addRank').modal('show');
    }

    function editRank(btn, id) {
        $.ajax({
            url: '{{ route('admin.forum.ajax_forum_get_rank') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
            },
            data: {
                id : id,    // id of selected record
            },
            success: function(response){
                let data = JSON.parse(response);
                $('#tid').html(id);

                $('#edit_id').val(data.id);
                $('#edit_name').val(data.name);
                if(data.staff) {
                    $('#edit_staff').prop('checked', true);
                } else {
                    $('#edit_staff').prop('checked', false);
                }
                $('#edit_background').val(data.background);
                $('#edit_color').val(data.color);
                $('#edit_background').next().val(data.background);
                $('#edit_color').next().val(data.color);

                $.ajax({
                    url: '{{ route('admin.forum.ajax_forum_get_rp') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
                    },
                    data: {
                        id: id,    // id of selected record
                    },
                    success: function (response) {
                        let datarp = JSON.parse(response);

                        datarp.forEach(function (e) {
                            let elem = $('#edit_perms_' + e['permission_id']);
                            elem.val(e['action']);
                            elem.next().prop({
                                indeterminate: false,
                                readonly: false,
                                checked: false
                            });
                            if(parseInt(e['action']) === 1) {
                                elem.next().prop({
                                    indeterminate: false,
                                    checked: true
                                });
                            } else if(parseInt(e['action']) === 2) {
                                elem.next().prop({
                                    indeterminate: true,
                                    readonly: true,
                                    checked: false
                                });
                            }
                        });
                    }
                });
            }
        });
        $('#editRank').modal('show');
    }

    function xhrPermissions(button) {

        if(button.is(':disabled')) {
            button.prop('disabled', false);
            button.html(element.data('value'));
        } else {
            button.attr('data-value', button.html());
            button.prop('disabled', true);
            button.html(loading_word + '...');
        }

        let route = "{{ route('admin.forum.generatePermissions') }}";

        Swal.fire({
            title: sure,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: yes,
            cancelButtonText: no
        }).then((result) => {
            if (result.value) {
                let xhr = new XMLHttpRequest();
                xhr.open('get', route);
                xhr.send();

                Toast().fire({
                    icon: "success",
                    title: updated
                });

                xhr.onload = function () {
                    console.log(xhr.response);
                };
                setTimeout(function () {
                    window.location.reload()
                }, 500)
            } else {
                if(button.is(':disabled')) {
                    button.prop('disabled', false);
                    button.html(element.data('value'));
                } else {
                    button.attr('data-value', button.html());
                    button.prop('disabled', true);
                    button.html(loading_word + '...');
                }
            }
        });
    }

    function defaultRank(id) {
        let xhr = new XMLHttpRequest();
        xhr.open('get', '{{ route('admin.forum.xhr_rank_default', ['']) }}/' + id);
        xhr.send();

        Toast().fire({
            icon: "success",
            title: updated
        });

        xhr.onload = function () {
            console.log(xhr.response);
        };
        setTimeout(function () {
            window.location.reload()
        }, 500)
    }
</script>
<script src="@PluginAssets('js/admin.js')"></script>
<script>
    const loading_word = "{{ __('forum_arckene::admin.loading') }}";
    const delete_sentence = "{{ __('forum_arckene::admin.Share.delete') }}";
    const yes = "{{ __('messages.YES') }}";
    const no = "{{ __('messages.NO') }}";
    const updated = "{{ __('messages.Updated') }}";
    const sure = "{{ __('forum_arckene::admin.sure') }}";
    const loading_sentence = "{{ __('forum_arckene::admin.loading_sentence') }}";

    ajaxSend($('#form__forum_rank'), $('#submit__forum_rank'), "{{ route('admin.forum.ajax_add_rank') }}", true);
    ajaxSend($('#form__forum_edit_rank'), $('#submit__forum_edit_rank'), "{{ route('admin.forum.ajax_edit_rank') }}", true);
</script>