<p>Вы успешно оформили подписку! ID вашей подписки - {{ $id }}</p>
<p>Ваша подписка активна до - {{ $time }}</p>
<form method="post" action="{{ secure_url(route('cancelPlan')) }}">
    <button type="submit">Отменить подписку</button>
</form>

<a href="http://127.0.0.1:50094/api/billing-plan">Вернуться на страницу подписки</a>
