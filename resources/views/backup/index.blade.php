@extends('layouts.app')

@section('title', 'backup')

@section('content')
<div class="container px-4">
    <h2 class="mb-5 text-center">Backup</h2>
    <div class="row">
        <div class="col-md-4 my-auto">
            <div class="card">
                <img src="{{ asset('assets/images/backup.png') }}" alt="Backup" class="img-card img-fluid rounded">
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-4 mb-5">
                <div class="import">
                    <h3 class="mb-4">Import Database</h3>
                    <form action="{{ route('backup.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-9 my-auto px-2">
                                <input type="file" name="file" required class="form-control" id="inputGroupFile">
                            </div>
                            <div class=" col-md-3 my-auto">
                                <button type="submit" class="text-center btn btn-primary btn-sm my-3">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card p-4 mb-5">
                <div class="export">
                    <h3 class="mb-4">Export Database</h3>
                    <a href="{{ route('backup.export') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-download mr-2"></i>Export Backup
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection