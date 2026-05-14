{{--
partials/permit-modal.blade.php
Shared "New Burial Permit" modal.
Include on any page that needs it: @include('partials.permit-modal')
Requires the page to also have a trigger button, e.g.:
onclick="document.getElementById('permitModal').classList.add('open')"
--}}

<div class="modal-overlay" id="permitModal" onclick="if(event.target===this)closePM()">
    <div class="modal" style="max-width:600px">
        <div class="modal-header">
            <h3>🪦 New Burial Permit</h3>
            <button class="modal-close" onclick="closePM()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('permits.store') }}">
            @csrf
            <div class="modal-body">

                {{-- REQUESTOR --}}
                <div class="form-group">
                    <label class="form-label">Requestor's Name <span style="color:#ef4444">*</span></label>
                    <input type="text" name="requestor_name" class="form-control" placeholder="Full name of requestor"
                        required value="{{ old('requestor_name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Requestor's Contact No.</label>
                    <input type="text" name="applicant_contact" class="form-control" placeholder="e.g. 09171234567"
                        value="{{ old('applicant_contact') }}">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-top:.6rem">
                    <div class="form-group">
                        <label class="form-label">O.R. Number <span style="font-weight:400;text-transform:none;font-size:10px;color:#9ca3af">optional</span></label>
                        <input type="text" name="or_number" class="form-control" placeholder="Receipt number" value="{{ old('or_number') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" id="pm_expiry_date" name="expiry_date" class="form-control" value="{{ old('expiry_date', now()->addYears(5)->format('Y-m-d')) }}">
                    </div>
                </div>

                {{-- DECEASED INFO --}}
                <div class="section-divider" style="margin-top:.5rem">Deceased Information</div>

                {{-- Name row: First · Middle · Last --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">First Name <span style="color:#ef4444">*</span></label>
                        <input type="text" name="first_name" class="form-control" placeholder="e.g. Juan" required
                            value="{{ old('first_name') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Middle Name <span
                                style="font-weight:400;text-transform:none;font-size:10px;color:#9ca3af">optional</span></label>
                        <input type="text" name="middle_name" class="form-control" placeholder="e.g. Santos"
                            value="{{ old('middle_name') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name <span style="color:#ef4444">*</span></label>
                        <input type="text" name="last_name" class="form-control" placeholder="e.g. Dela Cruz" required
                            value="{{ old('last_name') }}">
                    </div>
                </div>



                {{-- Nationality · Age · Sex --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-control" placeholder="e.g. Filipino"
                            value="{{ old('nationality') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control" placeholder="0" min="0"
                            value="{{ old('age') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sex</label>
                        <select name="sex" class="form-control">
                            <option value="">Select…</option>
                            <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                {{-- Date of Death · Kind of Burial --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.6rem">
                    <div class="form-group">
                        <label class="form-label">Date of Death <span style="color:#ef4444">*</span></label>
                        <input type="date" name="date_of_death" class="form-control" required
                            value="{{ old('date_of_death') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kind of Burial</label>
                        <select name="kind_of_burial" class="form-control">
                            <option value="">Select…</option>
                            <option value="Ground" {{ old('kind_of_burial') === 'Ground' ? 'selected' : '' }}>Ground
                            </option>
                            <option value="Niche" {{ old('kind_of_burial') === 'Niche' ? 'selected' : '' }}>Niche</option>
                        </select>
                    </div>
                </div>

                {{-- Place of Death · Residence --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">Place of Death</label>
                        <input type="text" name="place_of_death" class="form-control"
                            placeholder="e.g. Carmen, Davao del Norte" value="{{ old('place_of_death') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Residence</label>
                        <input type="text" name="address" class="form-control" placeholder="e.g. Brgy. Poblacion"
                            value="{{ old('address') }}">
                    </div>
                </div>

                {{-- FEES --}}
                <div class="section-divider" style="margin-top:.5rem">Burial Permit Fees</div>

                @php
                    $__settingsPath = storage_path('app/settings.json');
                    $__settings = file_exists($__settingsPath) ? json_decode(file_get_contents($__settingsPath), true) : [];

                    $__defaultFees = [
                        'cemented' => ['tomb' => 1000, 'permit' => 20, 'maint' => 100, 'app' => 20],
                        'niche_1st' => ['tomb' => 8000, 'permit' => 20, 'maint' => 100, 'app' => 20],
                        'niche_2nd' => ['tomb' => 6600, 'permit' => 20, 'maint' => 100, 'app' => 20],
                        'niche_3rd' => ['tomb' => 5700, 'permit' => 20, 'maint' => 100, 'app' => 20],
                        'niche_4th' => ['tomb' => 5300, 'permit' => 20, 'maint' => 100, 'app' => 20],
                        'bone_niches' => ['tomb' => 5000, 'permit' => 20, 'maint' => 100, 'app' => 20],
                    ];

                    $getFee = function ($key) use ($__settings, $__defaultFees) {
                        $raw = $__settings['fees'][$key] ?? $__defaultFees[$key] ?? $__defaultFees['cemented'];
                        $total = ($raw['tomb'] ?? 0) + ($raw['permit'] ?? 0) + ($raw['maint'] ?? 0) + ($raw['app'] ?? 0);
                        return number_format($total, 2);
                    };
                @endphp

                <div class="fee-grid">
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="cemented" id="pm_fee_cemented" {{ old('burial_fee_type') === 'cemented' ? 'checked' : '' }}>
                        <label for="pm_fee_cemented">Cemented</label>
                        <span class="fee-amount">₱{{ $getFee('cemented') }}</span>
                    </div>

                    <p
                        style="font-size:11px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:.4rem 0 .1rem .25rem">
                        Niches (New)</p>

                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_1st" id="pm_fee_1st" {{ old('burial_fee_type') === 'niche_1st' ? 'checked' : '' }}>
                        <label for="pm_fee_1st">1st Floor</label>
                        <span class="fee-amount">₱{{ $getFee('niche_1st') }}</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_2nd" id="pm_fee_2nd" {{ old('burial_fee_type') === 'niche_2nd' ? 'checked' : '' }}>
                        <label for="pm_fee_2nd">2nd Floor</label>
                        <span class="fee-amount">₱{{ $getFee('niche_2nd') }}</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_3rd" id="pm_fee_3rd" {{ old('burial_fee_type') === 'niche_3rd' ? 'checked' : '' }}>
                        <label for="pm_fee_3rd">3rd Floor</label>
                        <span class="fee-amount">₱{{ $getFee('niche_3rd') }}</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_4th" id="pm_fee_4th" {{ old('burial_fee_type') === 'niche_4th' ? 'checked' : '' }}>
                        <label for="pm_fee_4th">4th Floor</label>
                        <span class="fee-amount">₱{{ $getFee('niche_4th') }}</span>
                    </div>

                    <p
                        style="font-size:11px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:.4rem 0 .1rem .25rem">
                        Bone Niches</p>

                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="bone_niches" id="pm_fee_bone" {{ old('burial_fee_type') === 'bone_niches' ? 'checked' : '' }}>
                        <label for="pm_fee_bone">Bone Niches</label>
                        <span class="fee-amount">₱{{ $getFee('bone_niches') }}</span>
                    </div>
                </div>

            </div>{{-- /modal-body --}}

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closePM()">Cancel</button>
                <button type="submit" class="btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Create Permit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPM() { document.getElementById('permitModal').classList.add('open'); }
    function closePM() { document.getElementById('permitModal').classList.remove('open'); }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePM(); });
    if (new URLSearchParams(location.search).get('modal') === 'new' ||
        location.hash === '#new') {
        openPM();
        history.replaceState(null, '', location.pathname);
    }
</script>

<style>
    /* Modal scroll containment */
    #permitModal .modal {
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    #permitModal .modal form {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
    }

    #permitModal .modal-body {
        flex: 1;
        overflow-y: auto;
        overscroll-behavior: contain;
        padding: 1.25rem;
        background: #ffffff;
    }

    #permitModal .modal-footer {
        flex-shrink: 0;
    }

    /* Thin scrollbar */
    #permitModal .modal-body::-webkit-scrollbar {
        width: 4px;
    }

    #permitModal .modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    #permitModal .modal-body::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }

    #permitModal .modal-body::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Dark mode */
    html.dark #permitModal .modal-body::-webkit-scrollbar-thumb {
        background: #374151;
    }

    html.dark #permitModal .modal-body::-webkit-scrollbar-thumb:hover {
        background: #4b5563;
    }
</style>