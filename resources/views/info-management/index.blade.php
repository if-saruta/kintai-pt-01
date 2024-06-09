<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('情報管理') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__white-board --info-management-white-board">
            <div class="--info-management-white-board__index" id="index">
                <div class="btn-area">
                    <a href="{{ route('info-management.edit') }}" class="c-save-btn">編集</a>
                </div>
                <div class="info-list-wrap --index">
                    <div class="flex-box">
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">税率</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-late-input under-line">@if($info != null) {{ $info->tax_rate }} @endif</p>
                                <p class="c-p-head unit">%</p>
                            </div>
                        </div>
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">値引率</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-late-input under-line">@if($info != null) {{ $info->discount_rate }} @endif</p>
                                <p class="c-p-head">%</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-box">
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">月リース料</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->monthly_lease_fee }} @endif</p>
                                <p class="c-p-head">円</p>
                            </div>
                        </div>
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">月リース保険料</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->monthly_lease_insurance_fee }} @endif</p>
                                <p class="c-p-head">円</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-box">
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">月リース料(2代目)</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->monthly_lease_second_fee }} @endif</p>
                                <p class="c-p-head">円</p>
                            </div>
                        </div>
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">月リース保険料(2代目)</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->monthly_lease_second_insurance_fee }} @endif</p>
                                <p class="c-p-head">円</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-box">
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">日割りリース料</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->prorated_lease_fee }} @endif</p>
                                <p class="c-p-head">円</p>
                            </div>
                        </div>
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">日割り保険料</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->prorated_insurance_fee }} @endif</p>
                                <p class="c-p-head">円</p>
                            </div>
                        </div>
                    </div>
                    <div class="info-list-wrap__list-item">
                        <p class="c-p-head">業務委託率</p>
                        <div class="info-list-wrap__list-item__input-box">
                            <p class="value-txt c-p-head w-late-input under-line">@if($info != null) {{ $info->admin_commission_rate }} @endif</p>
                            <p class="c-p-head">%</p>
                        </div>
                    </div>
                    <div class="info-list-wrap__list-item">
                        <p class="c-p-head">事務手数料</p>
                        <div class="flex-box">
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->admin_fee_switch }} @endif</p>
                                <p class="c-p-head">円以上の場合</p>
                            </div>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->max_admin_fee }} @endif</p>
                                <p class="c-p-head">円</p>
                            </div>
                        </div>
                        <div class="flex-box">
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->admin_fee_switch }} @endif</p>
                                <p class="c-p-head">円未満の場合</p>
                            </div>
                            <div class="info-list-wrap__list-item__input-box">
                                <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->min_admin_fee }} @endif</p>
                                <p class="c-p-head">円</p>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="info-list-wrap__list-item">
                        <p class="c-p-head">振込手数料</p>
                        <div class="info-list-wrap__list-item__input-box">
                            <p class="value-txt c-p-head w-fee-input under-line">@if($info != null) {{ $info->transfer_fee }} @endif</p>
                            <p class="c-p-head">円</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/info-management.js')}}"></script>
