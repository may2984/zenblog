@push('css')
<style type="text/css">
  .bi-trash3:hover,
  .bi-pen-fill:hover {
    cursor: pointer
  }

  .error_text {
    height: 7px;
  }
</style>
<link href='https://guillotine.js.org/css/demo.min.css' rel='stylesheet'>
<link href='//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css' rel='stylesheet'>
@endpush
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
  $(function() {

    $.fn.getOptions = function(url, element_id, append) {
      // $('#list').html('<img src="{{ asset("backend/assets/img/Bars-1s-200px.gif") }}" height=50>');
      $.get(url, function(data, response) {
        $.fn.setOptions(data, element_id, append);
      });
    };

    $.fn.setTrips = function() {
      $.fn.getOptions('{{ route("trip.all") }}', 'trip_id');
    }

    $.fn.setTrips();

    $.fn.setOptions = function(members, element_id, append = true) {
      var option = $.fn.selectOptions(members);
      if (append) {
        $('#' + element_id).append(option);
      } else {
        $('#' + element_id).html(option);
      }
    };

    $.fn.setMembers = function(trip_id = 0) {
      var url = `/admin/trip/${trip_id}/members`;
      $.fn.getOptions(url, 'member_id', false);
    }

    $.fn.selectOptions = function(data) {
      var option = '';
      $.each(data, function(key, value) {
        option += `<option value="${value.id}">${value.name}</option>`
      });
      return option;
    }

  });
</script>
@endpush

<x-admin.layout>
  <x-slot:title>Add {{$label}}</x-slot:title>
  <section class="section">
    <div class="row">
      <div class="col-lg-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add {{$label}}</h5>
            <x-admin.form.alert_span />
            <form action="{{ route('trip.expenses.store') }}" id="form" method="post">
              @csrf()              
              <div class="row mb-3">
                <label for="inputPassword" class="col-sm-2 col-form-label">Trip</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Select Trip" name="trip_id" id="trip_id" onchange="$.fn.setMembers($(this).val());">
                    <option value="0">Select Trip</option>
                  </select>
                </div>
              </div>
              <div class="row mb-4"><span class="text-danger error_text" id="members_error"></span></div>
              <div class="row mb-3">
                <label for="inputPassword" class="col-sm-2 col-form-label">Members</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="select" id="member_id" name="member_id"></select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputNumber" class="col-sm-2 col-form-label">Item</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="item" id="item">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputDate" class="col-sm-2 col-form-label">Date</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" name="date_time" id="date_time">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputNumber" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="amount" id="amount">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Save</button>
                  <button id="cancel" class="btn btn-primary">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{$label}} List
              <div class="float-end fs-8 text-success hidden" id="success-message-ajax"></div>
              <div class="float-end fs-8 text-danger hidden" id="error-message-ajax"></div>
            </h5>
            <table class="table" id="list"></table>
            <div id="button"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-admin.layout>