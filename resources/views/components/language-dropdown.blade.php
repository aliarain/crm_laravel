<div class="single-select d-none d-lg-block">
    <input type="hidden" id="change_lang_url" value="{{ route('language.ajaxLangChange') }}">
    <select name="user_lang" class="language-select" id="change-user-lang">
        @foreach ($data['languages'] as $language)
            <option value="{{ $language->code }}" {{ $language->code == userLocal() ? 'selected' : '' }}>
                {{ $language->name }}</option>
        @endforeach
    </select>
</div>
