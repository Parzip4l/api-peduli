@extends('layouts.vertical', ['title' => 'Form Create Schedule Maintenance Item'])

@section('css')
@vite(['node_modules/choices.js/public/assets/styles/choices.min.css'])
@endsection

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush


@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4>Form Maintenance Schedule</h4>
            </div>
            <div class="card-body">
                <form action="{{route('maintenance.schedule.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Parent Item Name</label>
                            <select class="form-control" name="item_id" required>
                                <option value="">-- Select Item --</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" {{ $schedule->item_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select> 
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Schedule Type</label>
                            <select class="form-control" name="schedule" required>
                                <option value="">-- Select Type --</option>
                                <option value="Daily" {{ $schedule->schedule == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="Weekly" {{ $schedule->schedule == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="Monthly" {{ $schedule->schedule == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="Custom" {{ $schedule->schedule == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>   
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Next Maintenance</label>
                            <input type="date" class="form-control" name="next_maintenance" value="{{ $schedule->next_maintenance }}" required>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Update Schedule</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        @endif
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        const startDateContainer = document.getElementById("start-date-container");
        const startDateInput = document.getElementById("start-date");
        const scheduleType = document.getElementById("schedule-type");
        const nextMaintenance = document.getElementById("next-maintenance");

        function handleScheduleChange() {
            let scheduleValue = scheduleType.value;

            if (scheduleValue === "Custom") {
                // Hide Start Date & enable manual input for Next Maintenance
                startDateContainer.style.display = "none";
                startDateInput.value = "";
                nextMaintenance.value = "";
                nextMaintenance.disabled = false;
            } else {
                // Show Start Date & auto-calculate Next Maintenance
                startDateContainer.style.display = "block";
                if (startDateInput.value) {
                    calculateNextMaintenance();
                }
            }
        }

        function calculateNextMaintenance() {
            let startDate = new Date(startDateInput.value);
            if (isNaN(startDate)) return; // Jika tanggal belum dipilih, keluar dulu

            let nextDate = new Date(startDate);

            if (scheduleType.value === "Daily") {
                nextDate.setDate(startDate.getDate() + 1); // +1 hari
            } 
            else if (scheduleType.value === "Weekly") {
                nextDate.setDate(startDate.getDate() + 7); // +7 hari
            } 
            else if (scheduleType.value === "Monthly") {
                nextDate.setMonth(startDate.getMonth() + 1); // +1 bulan
            } 

            nextMaintenance.value = nextDate.toISOString().split('T')[0];
            nextMaintenance.readOnly = true; // Disable input jika bukan Custom
        }

        startDateInput.addEventListener("change", calculateNextMaintenance);
        scheduleType.addEventListener("change", handleScheduleChange);

        // Panggil sekali saat halaman dimuat
        handleScheduleChange();
    });
    </script>
@endsection