<link rel="stylesheet" href="@PluginAssets('css/admin.css')">

@foreach($icons as $i)
    @if($i['type'] == 1)
        <link rel="stylesheet" href="{{ $i['import'] }}" type="text/css">
    @elseif($i['type'] == 2)
        <script src="{{ $i['import'] }}"></script>
    @endif
@endforeach

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.1/angular.min.js'></script>

<?php
$GLOBALS['forum'] = $forum;

function nestableForum($id) {
    $forum = $GLOBALS['forum'];
    foreach ($forum->where('forum_id', $id) as $f) {
        if(!$f['category']) {
            ?>
            <li class="dd-item dd3-item" data-id="{{ $f['id'] }}">
                <div class="dd-handle dd3-handle">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="dd3-content">
                    <span class="py-2 d-inline-block"><span class="mr-2">#{{$f['id']}}</span>{{ $f['name'] }}</span>
                    <div class="float-right">
                        <button id="edit" onclick="modalEdit(this, {{ $f['id'] }})" class="btn btn-primary mr-2">
                                <i class="fas fa-edit"></i>
                        </button>

                        <button id="delete" onclick="xhrDelete(this, '{{ route('admin.forum.xhr_forum_delete', ['id' => $f['id']]) }}' , {{ $f['id'] }})" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </li>
            <?php } else { ?>
            <li class="dd-item dd3-item" data-id="{{ $f['id'] }}">
                <div class="dd-handle dd3-handle">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="dd3-content">
                    <span class="py-2 d-inline-block"><span class="mr-2">#{{$f['id']}}</span>{{ $f['name'] }}</span>
                    <div class="float-right">
                        <button id="edit" onclick="modalEdit(this, {{ $f['id'] }})" class="btn btn-primary mr-2">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button id="delete" onclick="xhrDelete(this, '{{ route('admin.forum.xhr_forum_delete', ['id' => $f['id']]) }}' , {{ $f['id'] }})" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <ol class="dd-list">
                    @php(nestableForum($f['id']))
                </ol>
            </li>
            <?php
        }
    }
}
?>

<div class="row" ng-app>
    <div class="col-xl-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="nestable-pos"><i class="fas fa-align-left"></i> {{ __('forum_arckene::admin.Forum.title') }}</h6>
            </div>
            <div class="card-body">
                <div class="dd">
                    <ol class="dd-list">
                        @php(nestableForum(0))
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus"></i> {{ __('forum_arckene::admin.Forum.Adding.title') }}</h6>
            </div>

            <div class="card-body">

                <div class="alert alert-info">
                    {{ __('forum_arckene::admin.Forum.Adding.toRank') }} <a href="{{ route('admin.forum.ranks') }}">({{ __('forum_arckene::admin.here') }})</a>
                </div>

                <form id="form__forum_forum">

                    <label for="name">{{ __('forum_arckene::admin.Forum.Adding.name') }}</label>
                    <input type="text" name="name" id="name" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Forum.Adding.name') }}" required>

                    <label for="description">{{ __('forum_arckene::admin.Forum.Adding.description') }}</label>
                    <textarea name="description" id="description" cols="30" rows="5" class="form-control shadow  mb-3" placeholder="{{ __('forum_arckene::admin.Forum.Adding.description') }}"></textarea>

                    <label for="size">{{ __('forum_arckene::admin.Forum.Adding.size') }}</label>
                    <input type="range" min="0" max="4" value="4" step="1" class="form-control-range shadow  mb-3" name="size" id="size" required>






                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="write__rank_id">Grade requis pour poster</label>
                                <select class="form-control shadow card" name="write__rank_id" id="write__rank_id">
                                    @foreach($ranks as $r)
                                        <option value="{{ $r['id'] }}" @if($r['default']) selected @endif>{{ rankName($r['name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="watch_rank_id">Grade requis pour acceder</label>
                                <select class="form-control shadow card" name="watch_rank_id" id="watch_rank_id">
                                    @foreach($ranks as $r)
                                        <option value="{{ $r['id'] }}" @if($r['default']) selected @endif>{{ rankName($r['name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="icon">{{ __('forum_arckene::admin.Forum.Adding.icon') }}</label>
                            <input type="text" name="icon" id="icon" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Forum.Adding.icon') }}" required>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="icon_type">{{ __('forum_arckene::admin.Forum.Adding.icon_type') }}</label>
                                <select class="form-control shadow card" name="icon_type" id="icon_type" required>
                                    <option value="0">Image</option>
                                    @foreach($icons as $i)
                                        <option value="{{ $i['id'] }}">{{ $i['name'] }} ({{ $i['website'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button id="submit__forum_forum" type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-paper-plane"></i>
                        </span>
                        <span class="text">
                            {{ __('forum_arckene::admin.save') }}
                        </span>
                    </button>

                </form>

            </div>

        </div>
    </div>
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tag"></i> Tags</h6>
            </div>
            <div class="card-body">
                <table class="table" id="share_tab">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>Name</th>
                        <th>Ranks</th>
                        <th>Forums</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($tags as $w)
                        <tr>
                            <th scope="row">{{ $w['id'] }}</th>
                            <td>
                                <span class="badge-lg badge-pill" style="background: {{ $w['background_color'] }}; color: {{ $w['text_color'] }};">{{ $w['name'] }}</span>
                            </td>
                            <td>
                                @if(strpos($w['rank_id'], "All") || is_null($w['rank_id']))
                                    <span class="badge-lg badge-pill badge-primary">All</span>
                                @else
                                    @php($array = json_decode($w['rank_id'], true))
                                    @for($i = 0; $i < count($array); $i++)
                                        <span class="badge-lg badge-pill" style="background: {{ $ranks->find($array[$i])['background'] }}; color: {{ $ranks->find($array[$i])['color'] }};">{{ rankName($ranks->find($array[$i])['name']) }}</span>
                                    @endfor
                                @endif
                            </td>
                            <td>
                                @if(strpos($w['thread_id'], "All") || is_null($w['thread_id']))
                                    <span class="badge-lg badge-pill badge-primary">All</span>
                                @else
                                    @php($array2 = json_decode($w['thread_id'], true))
                                    @for($i = 0; $i < count($array2); $i++)
                                        <span class="badge-lg badge-pill badge-info">{{ $forum->find($array2[$i])['name'] }}</span>

                                    @endfor
                                @endif
                            </td>
                            <td>
                                <button id="delete" onclick="xhrDelete(this, '{{ route('admin.forum.xhr_tag_delete', [$w['id']]) }}' , {{ $w['id'] }})" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="5">{{ __('forum_arckene::admin.Share.empty') }}</th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <h5 class="mb-3">Adding tags</h5>
                <form id="form__forum_add_tags">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="tags_name">Name</label>
                            <input type="text" name="tags_name" id="tags_name" class="form-control shadow card mb-3" required>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tags_forum">{{ __('forum_arckene::admin.Share.name') }}</label>
                                <select class="form-control shadow card" name="tags_forum[]" id="tags_forum" multiple>
                                    <option selected>All</option>
                                    @foreach($forum as $f)
                                        <option value="{{ $f['id'] }}">{{ $f['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tags_rank">{{ __('forum_arckene::admin.Share.icon_type') }}</label>
                                <select class="form-control shadow card" name="tags_rank[]" id="tags_rank" multiple>
                                    <option selected>All</option>
                                    @foreach($ranks as $f)
                                        <option value="{{ $f['id'] }}">{{ rankName($f['name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <label for="tags_color">Tags color</label>
                            <label class="color-picker mb-3" ng-init="tags_color='#666666' ">
                                <input class="color" type="color" id="tags_color" ng-model="tags_color">
                                <input class="hex" type="text"  ng-model="tags_color" name="tags_color" onfocus="this.select()" readonly>
                            </label>
                        </div>

                        <div class="col-md-6">
                            <label for="tags_background">Tags background</label>
                            <label class="color-picker mb-3" ng-init="tags_background='#111111' ">
                                <input class="color" type="color" id="tags_background" ng-model="tags_background">
                                <input class="hex" type="text"  ng-model="tags_background" name="tags_background" onfocus="this.select()" readonly>
                            </label>
                        </div>


                    </div>
                    <button id="submit__forum_add_tags" type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">
                            {{ __('forum_arckene::admin.add') }}
                        </span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('forum_arckene::admin.Forum.Adding.modal_title') }}<span id="tid"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form__forum_forum_edit">

                <div class="modal-body">

                    <input type="hidden" id="modal_id" name="id">

                    <label for="modal_name">{{ __('forum_arckene::admin.Forum.Adding.name') }}</label>
                    <input type="text" name="name" id="modal_name" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Forum.Adding.name') }}" required>

                    <label for="modal_description">{{ __('forum_arckene::admin.Forum.Adding.description') }}</label>
                    <textarea name="description" id="modal_description" cols="30" rows="5" class="form-control shadow  mb-3" placeholder="{{ __('forum_arckene::admin.Forum.Adding.description') }}"></textarea>

                    <label for="modal_size">{{ __('forum_arckene::admin.Forum.Adding.size') }}</label>
                    <input type="range" min="0" max="4" step="1" class="form-control-range shadow  mb-3" name="size" id="modal_size" required>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="write__rank_id">Grade requis pour poster</label>
                                <select class="form-control shadow card" name="write__rank_id" id="modal_write__rank_id">
                                    @foreach($ranks as $r)
                                        <option value="{{ $r['id'] }}">{{ rankName($r['name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="watch_rank_id">Grade requis pour acceder</label>
                                <select class="form-control shadow card" name="watch_rank_id" id="modal_watch_rank_id">
                                    @foreach($ranks as $r)
                                        <option value="{{ $r['id'] }}">{{ rankName($r['name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <label for="modal_icon">{{ __('forum_arckene::admin.Forum.Adding.icon') }}</label>
                            <input type="text" name="icon" id="modal_icon" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Forum.Adding.icon') }}" required>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label for="modal_icon_type">{{ __('forum_arckene::admin.Forum.Adding.icon_type') }}</label>
                                <select class="form-control shadow card" name="icon_type" id="modal_icon_type" required>
                                    <option value="0">Image</option>
                                    @foreach($icons as $i)
                                        <option value="{{ $i['id'] }}">{{ $i['name'] }} ({{ $i['website'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="submit__forum_forum_edit" type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-paper-plane"></i>
                        </span>
                        <span class="text">
                            {{ __('forum_arckene::admin.edit') }}
                        </span>
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>

        </div>
    </div>
</div>


<script>
    let editModal = $('#editModal');
    function modalEdit(element, id) {
        $('#editModal h5.modal-title').html($('#editModal h5.modal-title').html());

        $.ajax({
            url: '{{ route('admin.forum.ajax_forum_get_edit') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
            },
            data: {
                id : id,    // id of selected record
            },
            success: function(response){
                let data = JSON.parse(response);
                console.log(data);
                $('#tid').html(id);

                $('#modal_id').val(data.id);
                $('#modal_name').val(data.name);
                $('#modal_icon').val(data.icon);
                $('#modal_icon_type').val(data.icon_type);
                $('#modal_description').val(data.description);
                $('#modal_write__rank_id').val(data.write__rank_id);
                $('#modal_watch_rank_id').val(data.watch_rank_id);
                if(data.size != null) {
                    $('#modal_size').val(data.size);
                } else {
                    $('#modal_size').val(4);
                }

            }
        });

        editModal.modal('show');
    }

    function loadHeader(element) {
        if(element.hasClass('isLoading')) {
            element.html(element.data('html'));
        } else {
            element.attr('data-html', element.html());
            element.html("<i class=\"fas fa-spinner fa-spin\"></i> {{ __('forum_arckene::admin.saving') }}");
        }
        element.toggleClass("isLoading")
    }

    $('.dd').nestable({
        maxDepth: 5
    }).on('change',function(e){
        let json = JSON.stringify($('.dd').nestable('serialize'));
        loadHeader($('#nestable-pos'));
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.forum.ajax_forum_nestable') }}',
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

<script src="@PluginAssets('js/admin.js')"></script>
<script>
    const loading_word = "{{ __('forum_arckene::admin.loading') }}";
    const delete_sentence = "{{ __('forum_arckene::admin.Share.delete') }}";
    const yes = "{{ __('messages.YES') }}";
    const no = "{{ __('messages.NO') }}";
    const updated = "{{ __('messages.Updated') }}";

    ajaxSend($('#form__forum_forum'), $('#submit__forum_forum'), "{{ route('admin.forum.ajax_forum') }}", true);
    ajaxSend($('#form__forum_forum_edit'), $('#submit__forum_forum_edit'), "{{ route('admin.forum.ajax_forum_edit') }}", true);
    ajaxSend($('#form__forum_add_tags'), $('#submit__forum_add_tags'), "{{ route('admin.forum.ajax_forum_tags') }}", true);

</script>