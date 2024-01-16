<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件従業員別賃金') }}
        </h2>
    </x-slot>

    <div class="main">
        <p>案件名 : {{ $project->name }}</p>

        <div class="paymentShow">
            @if($payments->isEmpty())
                <p>登録されていません</p>
            @else
                @foreach ($payments as $payment)
                <div class="payment-show-content">
                    <p>{{$payment->employee->name}}</p>
                    @if ($payment->payment_type == 0)
                        <p>歩合</p>
                    @else
                        <p>時給</p>
                    @endif
                    @if ($payment->amount)
                        <p>￥{{$payment->amount}}</p>
                    @else
                        <p>未登録</p>
                    @endif
                </div>
                @endforeach
            @endif
        </div>
    </div>



</x-app-layout>
