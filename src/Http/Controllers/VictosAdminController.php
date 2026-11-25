<?php

namespace Earth2024\VictosStatus\Http\Controllers;

use Earth2024\VictosStatus\Models\CardSetting;
use Earth2024\VictosStatus\Models\StatusRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VictosAdminController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();
        $settings = CardSetting::forUser($user);
        $records = StatusRecord::query()
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->paginate(25);
        $summary = StatusRecord::query()
            ->where('user_id', $user->id)
            ->selectRaw('COUNT(*) AS record_count, COALESCE(SUM(total_eligible_amount), 0) AS eligible_total, COALESCE(SUM(eligible_amount_paid), 0) AS eligible_paid')
            ->first();
        $eligibleTotal = (float) $summary->eligible_total;
        $eligiblePaid = (float) $summary->eligible_paid;
        $dashboardStats = [
            'record_count' => (int) $summary->record_count,
            'eligible_total' => $eligibleTotal,
            'eligible_paid' => $eligiblePaid,
            'refunded_percent' => $eligibleTotal > 0
                ? round(min(100, max(0, $eligiblePaid / $eligibleTotal * 100)), 1)
                : 0,
        ];

        return view('victos-status::victos.dashboard', compact('settings', 'records', 'user', 'dashboardStats'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'card_title' => ['required', 'string', 'max:255'],
            'eligibility_badge_text' => ['required', 'string', 'max:255'],
            'connected_wallet_label' => ['required', 'string', 'max:255'],
            'total_refund_label' => ['required', 'string', 'max:255'],
            'signature_percentage_label' => ['required', 'string', 'max:255'],
            'refund_status_label' => ['required', 'string', 'max:255'],
            'signature_progress_label' => ['required', 'string', 'max:255'],
            'card_bg_from' => ['required', 'string', 'max:255'],
            'card_bg_to' => ['required', 'string', 'max:255'],
            'card_border_color' => ['required', 'string', 'max:255'],
            'badge_bg_color' => ['required', 'string', 'max:255'],
            'badge_text_color' => ['required', 'string', 'max:255'],
            'accent_color' => ['required', 'string', 'max:255'],
            'progress_bar_color' => ['required', 'string', 'max:255'],
            'amount_color' => ['required', 'string', 'max:255'],
            'check_status_button_text' => ['required', 'string', 'max:255'],
            'check_status_button_text_color' => ['required', 'string', 'max:255'],
            'check_status_button_bg_color' => ['required', 'string', 'max:255'],
            'check_status_button_font_size' => ['required', 'string', 'max:255'],
            'modal_header_text' => ['required', 'string', 'max:255'],
            'modal_address_label_text' => ['required', 'string', 'max:255'],
            'modal_submit_text' => ['required', 'string', 'max:255'],
            'card_title_font_size' => ['required', 'string', 'max:255'],
            'badge_font_size' => ['required', 'string', 'max:255'],
            'section_label_font_size' => ['required', 'string', 'max:255'],
            'wallet_value_font_size' => ['required', 'string', 'max:255'],
            'amount_font_size' => ['required', 'string', 'max:255'],
            'stat_label_font_size' => ['required', 'string', 'max:255'],
            'stat_value_font_size' => ['required', 'string', 'max:255'],
            'progress_label_font_size' => ['required', 'string', 'max:255'],
            'progress_percent_font_size' => ['required', 'string', 'max:255'],
            'remaining_font_size' => ['required', 'string', 'max:255'],
        ]);

        $settings = CardSetting::forUser($request->user());
        $settings->update($data);

        return back()->with('success', 'Card settings updated.');
    }

    public function createRecord(): View
    {
        $settings = CardSetting::forUser(auth()->user());
        $record = new StatusRecord([
            'card_title' => $settings->card_title,
            'eligibility_badge_text' => $settings->eligibility_badge_text,
            'connected_wallet_label' => $settings->connected_wallet_label,
            'total_refund_label' => $settings->total_refund_label,
            'signature_percentage_label' => $settings->signature_percentage_label,
            'refund_status_label' => $settings->refund_status_label,
            'signature_progress_label' => $settings->signature_progress_label,
            'wallet_display' => '0x000...0000',
            'total_refund_amount' => '$0.00',
            'total_eligible_amount' => 0,
            'eligible_amount_paid' => 0,
            'refund_status_value' => 'Pending',
            'signature_progress_status' => 'In progress',
            'card_bg_from' => $settings->card_bg_from,
            'card_bg_to' => $settings->card_bg_to,
            'card_border_color' => $settings->card_border_color,
            'badge_bg_color' => $settings->badge_bg_color,
            'badge_text_color' => $settings->badge_text_color,
            'accent_color' => $settings->accent_color,
            'progress_bar_color' => $settings->progress_bar_color,
            'amount_color' => $settings->amount_color,
            'card_title_font_size' => $settings->card_title_font_size,
            'badge_font_size' => $settings->badge_font_size,
            'section_label_font_size' => $settings->section_label_font_size,
            'wallet_value_font_size' => $settings->wallet_value_font_size,
            'amount_font_size' => $settings->amount_font_size,
            'stat_label_font_size' => $settings->stat_label_font_size,
            'stat_value_font_size' => $settings->stat_value_font_size,
            'progress_label_font_size' => $settings->progress_label_font_size,
            'progress_percent_font_size' => $settings->progress_percent_font_size,
            'remaining_font_size' => $settings->remaining_font_size,
        ]);

        return view('victos-status::victos.record-form', [
            'record' => $record,
            'settings' => $settings,
            'mode' => 'create',
        ]);
    }

    public function storeRecord(Request $request): RedirectResponse
    {
        $data = $this->validateRecord($request);
        $data['user_id'] = $request->user()->id;
        StatusRecord::query()->create($data);

        return redirect()->route('victos.dashboard')->with('success', 'Status record created.');
    }

    public function editRecord(StatusRecord $record): View
    {
        $settings = CardSetting::forUser(auth()->user());
        $record = $this->ownedRecord($record);

        return view('victos-status::victos.record-form', [
            'record' => $record,
            'settings' => $settings,
            'mode' => 'edit',
        ]);
    }

    public function updateRecord(Request $request, StatusRecord $record): RedirectResponse
    {
        $record = $this->ownedRecord($record);
        $data = $this->validateRecord($request, $record->id);
        $record->update($data);

        return redirect()->route('victos.dashboard')->with('success', 'Status record updated.');
    }

    public function destroyRecord(StatusRecord $record): RedirectResponse
    {
        $this->ownedRecord($record)->delete();

        return redirect()->route('victos.dashboard')->with('success', 'Status record deleted.');
    }

    private function validateRecord(Request $request, ?int $ignoreId = null): array
    {
        $user = $request->user();
        $uniqueRule = Rule::unique('status_records', 'address')
            ->where(fn ($query) => $query->where('user_id', $user->id));
        if ($ignoreId !== null) {
            $uniqueRule->ignore($ignoreId);
        }

        $data = $request->validate([
            'address' => ['required', 'string', 'max:255', $uniqueRule],
            'card_title' => ['nullable', 'string', 'max:255'],
            'eligibility_badge_text' => ['nullable', 'string', 'max:255'],
            'connected_wallet_label' => ['nullable', 'string', 'max:255'],
            'wallet_display' => ['nullable', 'string', 'max:255'],
            'total_refund_label' => ['nullable', 'string', 'max:255'],
            'total_refund_amount' => ['nullable', 'string', 'max:255'],
            'total_eligible_amount' => ['required', 'numeric', 'min:0'],
            'eligible_amount_paid' => ['required', 'numeric', 'min:0'],
            'signature_percentage_label' => ['nullable', 'string', 'max:255'],
            'refund_status_label' => ['nullable', 'string', 'max:255'],
            'refund_status_value' => ['nullable', 'string', 'max:255'],
            'signature_progress_label' => ['nullable', 'string', 'max:255'],
            'signature_progress_status' => ['nullable', 'string', 'max:255'],
            'card_bg_from' => ['nullable', 'string', 'max:255'],
            'card_bg_to' => ['nullable', 'string', 'max:255'],
            'card_border_color' => ['nullable', 'string', 'max:255'],
            'badge_bg_color' => ['nullable', 'string', 'max:255'],
            'badge_text_color' => ['nullable', 'string', 'max:255'],
            'accent_color' => ['nullable', 'string', 'max:255'],
            'progress_bar_color' => ['nullable', 'string', 'max:255'],
            'amount_color' => ['nullable', 'string', 'max:255'],
            'card_title_font_size' => ['nullable', 'string', 'max:255'],
            'badge_font_size' => ['nullable', 'string', 'max:255'],
            'section_label_font_size' => ['nullable', 'string', 'max:255'],
            'wallet_value_font_size' => ['nullable', 'string', 'max:255'],
            'amount_font_size' => ['nullable', 'string', 'max:255'],
            'stat_label_font_size' => ['nullable', 'string', 'max:255'],
            'stat_value_font_size' => ['nullable', 'string', 'max:255'],
            'progress_label_font_size' => ['nullable', 'string', 'max:255'],
            'progress_percent_font_size' => ['nullable', 'string', 'max:255'],
            'remaining_font_size' => ['nullable', 'string', 'max:255'],
        ]);

        // Derived fields are computed dynamically for display; clear any legacy manual values.
        $data['signature_percentage_value'] = null;
        $data['signature_progress_percent'] = null;
        $data['signature_progress_remaining'] = null;
        $data['total_refund_amount'] = trim((string) ($data['total_refund_amount'] ?? '')) !== ''
            ? trim((string) $data['total_refund_amount'])
            : '$' . number_format((float) $data['total_eligible_amount'], 2, '.', ',');

        return $data;
    }

    private function ownedRecord(StatusRecord $record): StatusRecord
    {
        return StatusRecord::query()
            ->where('user_id', auth()->id())
            ->findOrFail($record->id);
    }
}
