<!DOCTYPE html>
<html>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<head>
<div class="row">
    <div class="col-md-12"> 
        <div class="card card-primary card-outline">
             <form method="post" action="{{ route('importCSV') }}" enctype="multipart/form-data">
                @csrf
                <div class="row" style="padding:30px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                            <h3 for="exampleInputEmail1">Загрузка CSV </h3>
</head>
<body>
@if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
           @endif
           @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
           @endif
   

                           <div class="col-md-6">
                           <br>   <input id="csv_file" type="file" class="form-control" name="csv_file" required>
                          
                        </div>
                    </div>

                   
                    <br>
                    <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                     Импорт данных
                    </button>
                    </div>
                </div>

             </form>
</body>
             <div class="table-container">
            
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    
</div>
</html>