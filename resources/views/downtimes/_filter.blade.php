<div class="p-3">
    <form action="" method="GET">
        <input type="date" name="date_from" class="form-control w-auto d-inline me-4" value="{{ old('date_from') }}" onchange="this.form.submit();">                
        <input type="date" name="date_to" class="form-control w-auto d-inline" value="{{ old('date_to') }}" onchange="this.form.submit();">
    </form>
</div>
