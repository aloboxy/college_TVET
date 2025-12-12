<form method="post" action="{{ route('course.selector') }}">
            <fieldset>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                <select required onchange="getClassSubjects(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                    <option value="">Select Class</option>
                                    @foreach($my_classes as $c)
                                        <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                </fieldset>
            </div>

            <div class="col-md-2 mt-4">
                <div class="text-right mt-1">
                    <button type="submit" class="btn btn-primary">Manage Course <i class="icon-paperplane ml-2"></i></button>
                </div>
            </div>

        </div>

    </form>
