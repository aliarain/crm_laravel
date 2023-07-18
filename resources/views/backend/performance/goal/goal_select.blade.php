<div class="col-lg-6">
    <div class="form-group mb-3">
        <label class="form-label">{{ _trans('performance.Associate Goal') }}</label>
        <input hidden value="{{ _trans('project.Select A Goal') }}" id="select_goals">
        <select name="goal_id" class="form-control" id="goal_id" >
            @if (@$data['edit']->goal_id)
               <option value="{{ $data['edit']->goal_id }}">{{ $data['edit']->goal->subject }}</option>                
            @endif
        </select>
        @if ($errors->has('goal_id'))
            <div class="error">{{ $errors->first('goal_id') }}</div>
        @endif
    </div>
</div>