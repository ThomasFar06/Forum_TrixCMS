<link rel="stylesheet" href="@PluginAssets('css/admin.css')">

@foreach($icons as $i)
    @if($i['type'] == 1)
        <link rel="stylesheet" href="{{ $i['import'] }}" type="text/css">
    @elseif($i['type'] == 2)
        <script src="{{ $i['import'] }}"></script>
    @endif
@endforeach

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.1/angular.min.js'></script>
<div class="row" ng-app>
    <div class="col-xl">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-home"></i> {{ __('forum_arckene::admin.Config.title') }}</h6>
            </div>
            <div class="card-body">
                <form id="form__forum_config">
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="forum" name="forum" {{ $configuration['forum'] ? "checked" : "" }}>
                        <label class="custom-control-label" for="forum">{{ __('forum_arckene::admin.Config.forum') }}</label>
                    </div>
                    <div class="custom-control custom-switch mb-3" style="display: none">
                        <input type="checkbox" class="custom-control-input" id="theme" name="theme" {{ $configuration['theme'] ? "checked" : "" }}>
                        <label class="custom-control-label" for="theme">{{ __('forum_arckene::admin.Config.theme') }}</label>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="widget__button" name="widget__button" {{ $configuration['widget__button'] ? "checked" : "" }}>
                        <label class="custom-control-label" for="widget__button">{{ __('forum_arckene::admin.Config.widget__button') }}</label>
                    </div>
                    <div class="row mb-3" id="button-appear" {{ $configuration['widget__button'] ? "" : "style=display:none" }}>
                        <div class="col-lg">
                            <label for="button_link">{{ __('forum_arckene::admin.Config.button_link') }}</label>
                            <input type="text" name="button_link" id="button_link" class="form-control shadow card" placeholder="{{ __('forum_arckene::admin.Config.button_link') }}" value="{{ $configuration['button_link'] }}">
                        </div>
                        <div class="col-lg">
                            <label for="button_name">{{ __('forum_arckene::admin.Config.button_name') }}</label>
                            <input type="text" name="button_name" id="button_name" class="form-control shadow card" placeholder="{{ __('forum_arckene::admin.Config.button_name') }}" value="{{ $configuration['button_name'] }}">
                        </div>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="widget__staff" name="widget__staff" {{ $configuration['widget__staff'] ? "checked" : "" }}>
                        <label class="custom-control-label" for="widget__staff">{{ __('forum_arckene::admin.Config.widget__staff') }}</label>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="widget__online" name="widget__online" {{ $configuration['widget__online'] ? "checked" : "" }}>
                        <label class="custom-control-label" for="widget__online">{{ __('forum_arckene::admin.Config.widget__online') }}</label>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="widget__discord" name="widget__discord" {{ $configuration['widget__discord'] ? "checked" : "" }}>
                        <label class="custom-control-label" for="widget__discord">{{ __('forum_arckene::admin.Config.widget__discord') }}</label>
                    </div>
                    <div class="row mb-3" id="discord-appear" {{ $configuration['widget__discord'] ? "" : "style=display:none" }}>
                        <div class="col-md">
                            <label for="discord">{{ __('forum_arckene::admin.Config.discord') }}</label>
                            <input type="text" name="discord" id="discord" class="form-control shadow card" placeholder="{{ __('forum_arckene::admin.Config.discord') }}" value="{{ $configuration['discord'] }}">
                        </div>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="widget__statistics" name="widget__statistics" {{ $configuration['widget__statistics'] ? "checked" : "" }}>
                        <label class="custom-control-label" for="widget__statistics">{{ __('forum_arckene::admin.Config.widget__statistics') }}</label>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="widget__latest_post" name="widget__latest_post" {{ $configuration['widget__latest_post'] ? "checked" : "" }}>
                        <label class="custom-control-label" for="widget__latest_post">{{ __('forum_arckene::admin.Config.widget__latest_post') }}</label>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="widget__share" name="widget__share" {{ $configuration['widget__share'] ? "checked" : "" }}>
                        <label class="custom-control-label" for="widget__share">{{ __('forum_arckene::admin.Config.widget__share') }}</label>
                    </div>
                    <button id="submit__forum_config" type="submit" class="btn btn-primary btn-icon-split">
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
        <div class="card shadow mb-4" id="share-appear" {{ $configuration['widget__share'] ? "" : "style=display:none" }}>
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-share"></i> {{ __('forum_arckene::admin.Share.title') }}</h6>
            </div>
            <div class="card-body">
                <table class="table" id="share_tab">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>{{ __('forum_arckene::admin.Share.name') }}</th>
                        <th>{{ __('forum_arckene::admin.Share.icon') }}</th>
                        <th>{{ __('forum_arckene::admin.Share.color') }}</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>


                    @forelse($widgets as $w)
                        <tr>
                            <th scope="row">{{ $w['id'] }}</th>
                            <td>
                                @if($w['name'] == 1)
                                    Facebook (https://www.facebook.com/)
                                @elseif($w['name'] == 2)
                                    Twitter (https://twitter.com/)
                                @elseif($w['name'] == 3)
                                    Reddit (https://www.reddit.com/)
                                @elseif($w['name'] == 4)
                                    Pinterest (https://www.pinterest.fr/)
                                @elseif($w['name'] == 5)
                                    Tumblr (https://www.tumblr.com/)
                                @elseif($w['name'] == 6)
                                    WhatsApp (https://www.whatsapp.com/)
                                @elseif($w['name'] == 7)
                                    Messenger (https://www.messenger.com/)
                                @elseif($w['name'] == 8)
                                    Email
                                @else
                                    {{ __('forum_arckene::admin.Share.link') }}
                                @endif
                            </td>
                            <td>
                                @foreach($icons as $i)
                                    @if($w['icon_type'] == $i['id'])
                                        {!! str_replace("#icon#", $w['icon'], $i['format']) !!}
                                    @endif
                                @endforeach

                                ({{ $w['icon'] }})
                            </td>
                            <td>{{ $w['color'] }}</td>
                            <td>
                                <button id="delete" onclick="xhrDelete(this, '{{ route('admin.forum.xhr_share_delete', ['id' => $w['id']]) }}', {{ $w['id'] }})" type="submit" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">
                                        {{ __("forum_arckene::admin.delete") }}
                                    </span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="4">{{ __('forum_arckene::admin.Share.empty') }}</th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <h5 class="mb-3">{{ __('forum_arckene::admin.Share.adding') }}</h5>
                <form id="form__forum_add_sharable">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">{{ __('forum_arckene::admin.Share.name') }}</label>
                                <select class="form-control shadow card" name="name" id="name">
                                    <option value="1">Facebook (https://www.facebook.com/)</option>
                                    <option value="2">Twitter (https://twitter.com/)</option>
                                    <option value="3">Reddit (https://www.reddit.com/)</option>
                                    <option value="4">Pinterest (https://www.pinterest.fr/)</option>
                                    <option value="5">Tumblr (https://www.tumblr.com/)</option>
                                    <option value="6">WhatsApp (https://www.whatsapp.com/)</option>
                                    <option value="7">Messenger (https://www.messenger.com/)</option>
                                    <option value="8">Email</option>
                                    <option value="9">Lien</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="icon_type">{{ __('forum_arckene::admin.Share.icon_type') }}</label>
                                <select class="form-control shadow card" name="icon_type" id="icon_type">
                                    @foreach($icons as $i)
                                        <option value="{{ $i['id'] }}">{{ $i['name'] }} ({{ $i['website'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="icon">{{ __('forum_arckene::admin.Share.icon') }}</label>
                            <input type="text" name="icon" id="icon" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Share.icon') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="color">{{ __('forum_arckene::admin.Share.color') }}</label>
                            <label class="color-picker mb-3" ng-init="color='#666666' ">
                                <input class="color" type="color" id="color" ng-model="color">
                                <input class="hex" type="text"  ng-model="color" name="color" onfocus="this.select()" readonly>
                            </label>
                        </div>
                    </div>
                    <button id="submit__forum_add_sharable" type="submit" class="btn btn-primary btn-icon-split">
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
    <div class="col-xl">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit"></i> {{ __('forum_arckene::admin.Custom.title') }}</h6>
            </div>
            <div class="card-body">
                <form id="form__forum_custom">
                    <div class="row">
                        <div class="col-xl-6">
                            <label for="color__main">{{ __('forum_arckene::admin.Custom.color__main') }}</label>
                            <label class="color-picker mb-3" ng-init="color__main='{{ $customization['color__main'] ? $customization['color__main'] : "#999999" }}'; ">
                                <input class="color" type="color" id="color__main" ng-model="color__main">
                                <input class="hex" type="text"  ng-model="color__main" name="color__main" onfocus="this.select()" readonly>
                            </label>

                            <label for="color__second">{{ __('forum_arckene::admin.Custom.color__second') }}</label>
                            <label class="color-picker mb-3" ng-init="color__second='{{ $customization['color__second'] ? $customization['color__second'] : "#888888" }}'; ">
                                <input class="color" type="color" id="color__second" ng-model="color__second">
                                <input class="hex" type="text"  ng-model="color__second" name="color__second" onfocus="this.select()" readonly>
                            </label>

                            <label for="color__background">{{ __('forum_arckene::admin.Custom.color__background') }}</label>
                            <label class="color-picker mb-3" ng-init="color__background='{{ $customization['color__background'] ? $customization['color__background'] : "#777777" }}'; ">
                                <input class="color" type="color" id="color__background" ng-model="color__background">
                                <input class="hex" type="text"  ng-model="color__background" name="color__background" onfocus="this.select()" readonly>
                            </label>
                        </div>
                        <div class="col-xl-6">
                            <div class="custom-control custom-switch mb-3 mt-1">
                                <input type="checkbox" class="custom-control-input" id="forum__description_tooltip" name="forum__description_tooltip" {{ $customization['forum__description_tooltip'] ? "checked" : "" }}>
                                <label class="custom-control-label" for="forum__description_tooltip">{{ __('forum_arckene::admin.Custom.forum__description_tooltip') }}</label>
                            </div>
                            <div class="form-group mb-3">
                                <label for="forum__icon__default">{{ __('forum_arckene::admin.Custom.forum__icon__default') }}</label>
                                <select class="form-control shadow card" name="forum__icon__default" id="forum__icon__default">
                                    <option value="0" @if($customization['forum__icon__default'] == 0) selected @endif>Image</option>
                                    @foreach($icons as $i)
                                        <option value="{{ $i['id'] }}" @if($customization['forum__icon__default'] == $i['id']) selected @endif>{{ $i['name'] }} ({{ $i['website'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" class="custom-control-input" id="user__profile__tooltip" name="user__profile__tooltip" {{ $customization['user__profile__tooltip'] ? "checked" : "" }}>
                                <label class="custom-control-label" for="user__profile__tooltip">{{ __('forum_arckene::admin.Custom.user__profile__tooltip') }}</label>
                            </div>
                            <div class="form-group mb-3">
                                <label for="user__profile__avatar">{{ __('forum_arckene::admin.Custom.user__profile__avatar') }}</label>
                                <select class="form-control shadow card" id="user__profile__avatar" name="user__profile__avatar">
                                    <option value="1" @if($customization['user__profile__avatar'] == 1) selected @endif>Image</option>
                                    <option value="2" @if($customization['user__profile__avatar'] == 2) selected @endif>Gravatar (https://fr.gravatar.com/)</option>
                                    <option value="3" @if($customization['user__profile__avatar'] == 3) selected @endif>Minecraft Head (https://minotar.net/)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button id="submit__forum_custom" type="submit" class="btn btn-primary btn-icon-split">
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


        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info"></i> {{ __('forum_arckene::admin.Icon.title') }}</h6>
            </div>
            <div class="card-body">
                <table class="table" id="share_tab">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>{{ __('forum_arckene::admin.Icon.name') }}</th>
                        <th>{{ __('forum_arckene::admin.Icon.website') }}</th>
                        <th>{{ __('forum_arckene::admin.Icon.format') }}</th>
                        <th>{{ __('forum_arckene::admin.Icon.import') }}</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>


                    @forelse($icons as $i)
                        <tr>
                            <th scope="row">{{ $i['id'] }}</th>
                            <td>{{ $i['name'] }}</td>
                            <td>{{ $i['website'] }}</td>
                            <td>{{ $i['format'] }}</td>
                            <td style="text-align: center">
                                <span class="badge badge-primary" data-toggle="tooltip" data-placement="top" title="{{ $i['import'] }}">
                                    @if($i['type'] == 1)
                                        CSS
                                    @elseif($i['type'] == 2)
                                        JS
                                    @endif
                                </span>
                            </td>
                            <td>
                                <button id="delete" onclick="xhrDelete(this, '{{ route('admin.forum.xhr_icon_delete', ['id' => $i['id']]) }}' , {{ $i['id'] }})" type="submit" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">
                                        {{ __("forum_arckene::admin.delete") }}
                                    </span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="4">{{ __('forum_arckene::admin.Share.empty') }}</th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <h5 class="mb-3">{{ __('forum_arckene::admin.Icon.adding') }}</h5>
                <form id="form__forum_add_icon">
                    <div class="row">

                            <div class="col-md-6">
                                <label for="icon_name">{{ __('forum_arckene::admin.Icon.name') }}</label>
                                <input type="text" name="name" id="icon_name" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Icon.name') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="icon_website">{{ __('forum_arckene::admin.Icon.website') }}</label>
                                <input type="text" name="website" id="icon_website" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Icon.website') }}" required>
                            </div>

                        <div class="col-md-12">
                            <label for="icon_format">{{ __('forum_arckene::admin.Icon.format') }} <small>#icon# variable</small></label>
                            <input type="text" name="format" id="icon_format" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Icon.format') }}" required>
                        </div>

                        <div class="col-md-10">
                            <label for="icon_import">{{ __('forum_arckene::admin.Icon.import_link') }}</label>
                            <input type="text" name="import" id="icon_import" class="form-control shadow card mb-3" placeholder="{{ __('forum_arckene::admin.Icon.import_link') }}" required>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group mb-3">
                                <label for="icon_type">{{ __('forum_arckene::admin.Icon.import') }}</label>
                                <select class="form-control shadow card" name="type" id="icon_type">
                                    <option value="1">CSS (link)</option>
                                    <option value="2">JS (script)</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <button id="submit__forum_add_icon" type="submit" class="btn btn-primary btn-icon-split">
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


<script src="@PluginAssets('js/admin.js')"></script>
<script>
    const loading_word = "{{ __('forum_arckene::admin.loading') }}";
    const delete_sentence = "{{ __('forum_arckene::admin.Share.delete') }}";
    const yes = "{{ __('messages.YES') }}";
    const no = "{{ __('messages.NO') }}";
    const updated = "{{ __('messages.Updated') }}";

    ajaxSend($('#form__forum_config'), $('#submit__forum_config'), "{{ route('admin.forum.ajax_config') }}");
    ajaxSend($('#form__forum_custom'), $('#submit__forum_custom'), "{{ route('admin.forum.ajax_custom') }}");
    ajaxSend($('#form__forum_add_sharable'), $('#submit__forum_add_sharable'), "{{ route('admin.forum.ajax_share') }}", true);
    ajaxSend($('#form__forum_add_icon'), $('#submit__forum_add_icon'), "{{ route('admin.forum.ajax_icon') }}", true);
</script>