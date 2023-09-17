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

    // $.fn.setTrips();

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
      <div class="col-lg-7">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{$label}} List
              <div class="float-end fs-8 text-success hidden" id="success-message-ajax"></div>
              <div class="float-end fs-8 text-danger hidden" id="error-message-ajax"></div>
            </h5>
            <table class="table" id="list">
              <tr>
               <td>Name</td>
               <td>Item</td>
               <td>Amount</td>
               <td>Date</td>
              </tr>
              @foreach($expenses AS $expense)
              <tr>
                <td>{{ $expense->member->name }}</td>
                <td>{{ $expense->item }}</td>
                <td>{{ $expense->amount }}</td>
                <td>{{ $expense->date_time }}</td>
              </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-admin.layout>