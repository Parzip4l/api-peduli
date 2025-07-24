@extends('layouts.vertical', ['title' => 'Detail Research Data'])

@section('content')
<a href="{{route('log-riset-grease.index')}}" class="btn btn-sm btn-primary mb-2"><iconify-icon icon="mynaui:chevron-left-solid" class="align-middle fs-18"></iconify-icon> Kembali</a>
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card overflow-hidden" style="background: url('/images/small/img-2.jpg'); ">
            <div class="position-absolute top-0 end-0 bottom-0 start-0 bg-dark opacity-75"></div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-7 text-center">
                        <h3 class="text-white">Detail Research Data {{ $master->product_name }}</h3>
                        <p class="text-white-50">Research Code : <span class="text-primary" style="text-transform:uppercase">{{ $master->batch_code }}</span> || Expected Date Research : <span class="text-primary">{{ \Carbon\Carbon::parse($master->expected_start_date)->format('d F Y') }}</span>
                        @if (\Carbon\Carbon::parse($master->expected_start_date)->isPast())
                            <span style="font-size:12px" class="text-danger">( Past Deadline )</span>
                        @else
                            <span style="font-size:12px" class="text-success">( On Track )</span>
                        @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col">
        <div class="card">
            <div class="card-body p-4">
                <div class="row g-xl-4">
                    <div class="col-xl-12">

                        <h4 class="mb-3 fw-semibold fs-16">Log History Research {{ $master->product_name }}</h4>
                        <!-- FAQs -->
                        <div class="accordion">
                            @foreach($master->details as $key => $detail)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#details{{ $key }}" aria-expanded="true" aria-controls="details{{ $key }}">
                                        Detail #{{ $key + 1 }} {{$detail->created_at}} By {{$detail->created_by}}
                                    </button>
                                </h2>
                                <div id="details{{ $key }}" class="accordion-collapse collapse show" aria-labelledby="details{{ $key }}">
                                    <div class="accordion-body">
                                        <div class="content-details-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="data">
                                                    <div class="title">
                                                        <h5>Trial Methods :</h5>
                                                    </div>
                                                    <div class="content-details">
                                                        <p>{{$detail->trial_method}}</p>
                                                    </div>
                                                </div>
                                                <div class="data">
                                                    <div class="title">
                                                        <h5>Competitor Comparison :</h5>
                                                    </div>
                                                    <div class="content-details">
                                                        <p>{{$detail->competitor_comparison}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="title">
                                                <h5>Trial Result :</h5>
                                            </div>
                                            <div class="content-details">
                                                <p>{{$detail->trial_result}}</p>
                                            </div>
                                            <div class="title">
                                                <h5>Issue :</h5>
                                            </div>
                                            <div class="content-details">
                                                <p>{{$detail->issue}}</p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div class="data">
                                                    <div class="title">
                                                        <h5>Improvement Ideas :</h5>
                                                    </div>
                                                    <div class="content-details">
                                                        <p>{{$detail->improvement_ideas}}</p>
                                                    </div>
                                                </div>
                                                <div class="data">
                                                    <div class="title">
                                                        <h5>Improvement Schedule :</h5>
                                                    </div>
                                                    <div class="content-details">
                                                        <p>{{$detail->improvement_schedule}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div class="data">
                                                    <div class="title">
                                                        <h5>Status :</h5>
                                                    </div>
                                                    <div class="content-details">
                                                        <a class="btn btn-sm 
                                                            @if($detail->status == 'On Progress')
                                                                btn-primary
                                                            @elseif($detail->status == 'Done')
                                                                btn-success
                                                            @elseif($detail->status == 'On Hold')
                                                                btn-danger
                                                            @elseif($detail->status == 'Closed')
                                                                btn-secondary
                                                            @else
                                                                btn-secondary  <!-- Default if none of the above matches -->
                                                            @endif
                                                        ">
                                                            {{$detail->status}}
                                                        </a>
                                                    </div>
                                                </div>
                                                @if($detail->attachment)
                                                <div class="data flex-end">
                                                    <div class="title">
                                                        <h5>Attachment :</h5>
                                                    </div>
                                                    <a href="{{ asset('storage/' . $detail->attachment) }}" target="_blank" class="btn btn-sm btn-success">View Attachment</a>
                                                </div>
                                                @endif
                                            </div>  
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>

                </div> <!-- end row-->

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

@endsection