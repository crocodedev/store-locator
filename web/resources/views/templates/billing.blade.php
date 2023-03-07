<h1>Выберите тарифный план</h1>
<form method="post" action="{{ secure_url(route('activatePlan')) }}">
    @csrf

    @if($plans->count())
        <select name="plan">
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
            @endforeach
        </select>
    @endif

    <button type="submit">Отправить</button>
</form>
<form method="post" action="{{ secure_url(route('cancelPlan')) }}">
    <button type="submit">Отменить подписку</button>
</form>
