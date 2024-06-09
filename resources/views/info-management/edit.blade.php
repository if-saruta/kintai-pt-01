<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('情報管理') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__white-board --info-management-white-board">
            <div class="--info-management-white-board__index" id="edit">
                <div class="btn-area">
                    <a href="{{ route('info-management.') }}" class="c-back-btn">戻る</a>
                    <button class="c-save-btn" id="submit">登録</button>
                </div>
                <form action="{{ route('info-management.update') }}" method="POST" id="form">
                    @csrf
                    <div class="info-list-wrap">
                        <div class="flex-box">
                            <div class="info-list-wrap__list-item">
                                <p class="c-p-head">税率</p>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-late-input" name="tax_rate" @if($info != null) value="{{ $info->tax_rate }}" @endif placeholder="10" required>
                                    <p class="c-p-head">%</p>
                                </div>
                            </div>
                            <div class="info-list-wrap__list-item">
                                <p class="c-p-head">値引率</p>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-late-input" name="discount_rate" @if($info != null) value="{{ $info->discount_rate }}" @endif placeholder="10" required>
                                    <p class="c-p-head">%</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-box">
                            <div class="info-list-wrap__list-item">
                                <p class="c-p-head">月リース料</p>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input commaInput" name="monthly_lease_fee" @if($info != null) value="{{ $info->monthly_lease_fee }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                </div>
                            </div>
                            <div class="info-list-wrap__list-item">
                                <p class="c-p-head">月リース保険料</p>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input commaInput" name="monthly_lease_insurance_fee" @if($info != null) value="{{ $info->monthly_lease_insurance_fee }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-box">
                            <div class="info-list-wrap__list-item">
                                <p class="c-p-head">月リース料(2台目)</p>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input commaInput" name="monthly_lease_second_fee" @if($info != null) value="{{ $info->monthly_lease_second_fee }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                </div>
                            </div>
                            <div class="info-list-wrap__list-item">
                                <p class="c-p-head">月リース保険料(2台目)</p>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input commaInput" name="monthly_lease_second_insurance_fee" @if($info != null) value="{{ $info->monthly_lease_second_insurance_fee }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-box">
                            <div class="info-list-wrap__list-item">
                                <p class="c-p-head">日割りリース料</p>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input commaInput" name="prorated_lease_fee" @if($info != null) value="{{ $info->prorated_lease_fee }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                </div>
                            </div>
                            <div class="info-list-wrap__list-item">
                                <p class="c-p-head">日割り保険料</p>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input commaInput" name="prorated_insurance_fee" @if($info != null) value="{{ $info->prorated_insurance_fee }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">業務委託率</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <input type="text" class="c-input w-late-input" name="admin_commission_rate" @if($info != null) value="{{ $info->admin_commission_rate }}" @endif placeholder="10" required>
                                <p class="c-p-head">%</p>
                            </div>
                        </div>
                        <div class="info-list-wrap__list-item">
                            <p class="c-p-head">事務手数料</p>
                            <div class="flex-box flex-center">
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input amountLinkage commaInput" name="admin_fee_switch" @if($info != null) value="{{ $info->admin_fee_switch }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                    <p class="c-p-head">以上</p>
                                </div>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input commaInput" name="max_admin_fee" @if($info != null) value="{{ $info->max_admin_fee }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                </div>
                            </div>
                            <div class="flex-box flex-center">
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input amountLinkage commaInput" name="admin_fee_switch" @if($info != null) value="{{ $info->admin_fee_switch }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                    <p class="c-p-head">未満</p>
                                </div>
                                <div class="info-list-wrap__list-item__input-box">
                                    <input type="text" class="c-input w-fee-input commaInput" name="min_admin_fee" @if($info != null) value="{{ $info->min_admin_fee }}" @endif placeholder="1,000" required>
                                    <p class="c-p-head">円</p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="info-list-wrap__list-item">
                            <p class="c-p-head">振込手数料</p>
                            <div class="info-list-wrap__list-item__input-box">
                                <input type="text" class="c-input w-fee-input commaInput" name="transfer_fee" @if($info != null) value="{{ $info->transfer_fee }}" @endif placeholder="1,000" required>
                                <p class="c-p-head">円</p>
                            </div>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/info-management.js')}}"></script>
