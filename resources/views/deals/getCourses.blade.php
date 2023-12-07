{{ Form::label('courses', __('Courses'),['class'=>'form-label']) }}

<select class="form-control select2" name="courses[]" id="choices-multiple3" required>
<option value="" selected>Select Courses</option>
@foreach($courses as $course)
<option value="<?= $course->id ?>"><?= $course->name ?></option>
@endforeach
</select>