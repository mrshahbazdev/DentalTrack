<div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6 space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('app.scanner.title') }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                @if($step === 'scan_workstation')
                    {{ __('app.scanner.step1') }}
                @elseif($step === 'scan_order')
                    {{ __('app.scanner.step2') }}
                @elseif($step === 'confirm_action')
                    {{ __('app.scanner.step3') }}
                @elseif($step === 'scan_next_station')
                    {{ __('app.scanner.step4') }}
                @endif
            </p>
        </div>

        @if($errorMessage)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ $errorMessage }}
            </div>
        @endif

        @if($successMessage)
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ $successMessage }}
            </div>
        @endif

        @if($workstationName)
            <div class="bg-blue-50 border border-blue-200 px-4 py-3 rounded-lg">
                <span class="text-sm font-medium text-blue-800">{{ __('app.scanner.workstation') }}</span>
                <span class="text-blue-700">{{ $workstationName }}</span>
            </div>
        @endif

        @if($orderInfo)
            <div class="bg-purple-50 border border-purple-200 px-4 py-3 rounded-lg">
                <span class="text-sm font-medium text-purple-800">{{ __('app.scanner.order') }}</span>
                <span class="text-purple-700">{{ $orderInfo }}</span>
                @if($currentStepName)
                    <br>
                    <span class="text-sm font-medium text-purple-800">{{ __('app.scanner.current_step') }}</span>
                    <span class="text-purple-700">{{ $currentStepName }}</span>
                @endif
            </div>
        @endif

        @if(in_array($step, ['scan_workstation', 'scan_order', 'scan_next_station']))
            <div id="qr-reader" class="w-full rounded-lg overflow-hidden"></div>
        @endif

        @if($step === 'confirm_action')
            <div class="space-y-3">
                <textarea
                    wire:model="notes"
                    placeholder="{{ __('app.scanner.add_note') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    rows="2"
                ></textarea>

                <div class="grid grid-cols-1 gap-2">
                    <button
                        wire:click="performAction('start')"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition"
                    >
                        {{ __('app.scanner.start_work') }}
                    </button>
                    <button
                        wire:click="performAction('pause')"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-4 rounded-lg transition"
                    >
                        {{ __('app.scanner.pause') }}
                    </button>
                    <button
                        wire:click="performAction('complete')"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition"
                    >
                        {{ __('app.scanner.complete_next') }}
                    </button>
                </div>
            </div>
        @endif

        <button
            wire:click="resetScanState"
            class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition text-sm"
        >
            {{ __('app.scanner.reset') }}
        </button>
    </div>

    @if(in_array($step, ['scan_workstation', 'scan_order', 'scan_next_station']))
        @script
        <script>
            const html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    html5QrCode.stop();
                    $wire.processQrCode(decodedText);
                },
                () => {}
            ).catch(err => console.error("QR Scanner error:", err));

            $cleanup(() => {
                html5QrCode.stop().catch(() => {});
            });
        </script>
        @endscript
    @endif
</div>
