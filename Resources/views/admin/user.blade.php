<div class="row ng-scope" ng-app="">
    <div class="col-xl">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-home"></i> {{ user($user['id'])->pseudo }}</h6>
            </div>
            <div class="card-body">
                @if(userInfo($user['id'], 'banned'))
                    <div class="alert alert-danger">
                        Ce joueur est banni, <a href="#" onclick="unban()">cliquer ici</a> pour le débannir
                    </div>
                @endif

                <div class="row">
                    <div class="col-2">
                        <img src="{{ userInfo($user['id'], 'avatar') }}" alt="{{ user($user['id'])->pseudo }}" class="w-100">
                        <a href="#" class="btn btn-block btn-primary mt-3" onclick="avatarReset()">{{ __('forum_arckene::admin.User.image') }}</a>
                    </div>
                    <div class="col-10">
                        @php($userRanks = userRanks($user['id']))
                        @php($rankId = [])
                        @foreach($userRanks as $rank)
                            @php(array_push($rankId, $rank['id']))
                        @endforeach
                        <div class="form-group">
                            <a href="#" class="btn btn-block btn-danger mb-3" onclick="ban()">{{ __('forum_arckene::admin.User.ban') }}</a>
                            <form id="form__forum_ajax_rank">
                                <label for="rank">{{ __('forum_arckene::admin.User.ranks') }}</label>
                                <input type="hidden" value="{{ $user['id'] }}" name="id">
                                <select name="ranks[]" id="rank" class="form-control mb-4" multiple>

                                    @foreach($ranks as $r)
                                        <option value="{{ $r['id'] }}" @if(in_array($r['id'], $rankId)) selected @endif>{{ rankName($r['name']) }}</option>
                                    @endforeach
                                </select>
                                <button id="submit__forum_ajax_rank" type="submit" class="btn btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-paper-plane"></i>
                                    </span>
                                                <span class="text">
                                        {{ __('forum_arckene::admin.edit') }}
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function unban(e = event) {
        e.preventDefault()
        let xhr = new XMLHttpRequest();
        xhr.open('GET', "{{ route('admin.forum.user.xml.unban', [user()->id]) }}");
        xhr.send(null);
        setTimeout(function () {
            window.location.reload();
        }, 500);
    }

    function ban(e = event) {
        e.preventDefault()
        let xhr = new XMLHttpRequest();
        xhr.open('GET', "{{ route('admin.forum.user.xml.ban', [user()->id]) }}");
        xhr.send(null);
        setTimeout(function () {
            window.location.reload();
        }, 500);
    }

    function avatarReset(e = event) {
        e.preventDefault()
        let xhr = new XMLHttpRequest();
        xhr.open('GET', "{{ route('admin.forum.user.xml.avatarReset', [user()->id]) }}");
        xhr.send(null);
        setTimeout(function () {
            window.location.reload();
        }, 500);
    }


</script>

<script src="@PluginAssets('js/admin.js')"></script>
<script>
    const loading_word = "{{ __('forum_arckene::admin.loading') }}";
    const delete_sentence = "{{ __('forum_arckene::admin.Share.delete') }}";
    const yes = "{{ __('messages.YES') }}";
    const no = "{{ __('messages.NO') }}";
    const updated = "{{ __('messages.Updated') }}";

    ajaxSend($('#form__forum_ajax_rank'), $('#submit__forum_ajax_rank'), "{{ route('admin.forum.user.ajax.rank') }}", true);

</script>