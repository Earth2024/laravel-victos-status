<?php

namespace Earth2024\VictosStatus\Http\Controllers;

use Earth2024\VictosStatus\Models\CardSetting;
use Earth2024\VictosStatus\Models\StatusRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StatusCheckController extends Controller
{
    public function check(Request $request): JsonResponse
    {
        $tenant = $this->tenantForRequest($request);
        if (!$tenant) {
            return response()->json(['found' => false, 'message' => 'This status service is not configured for this website.'], 403)
                ->header('Vary', 'Origin');
        }

        $address = strtolower(trim((string) $request->query('address', '')));
        if ($address === '') {
            return $this->json($request, $tenant, ['found' => false, 'message' => 'Please enter an address.'], 422);
        }

        $record = StatusRecord::query()
            ->where('user_id', $tenant->id)
            ->where('address', $address)
            ->first();

        if (!$record) {
            return $this->json($request, $tenant, ['found' => false, 'message' => 'No such record found for this address.'], 404);
        }

        $settings = CardSetting::forUser($tenant);
        $refundedPercent = $record->refundedPercentage();
        $remainingPercent = $record->remainingPercentage();
        $percentText = $record->formatPercent($refundedPercent);
        $remainingText = $record->formatPercent($remainingPercent) . ' remaining';

        $d = [
            'card_title' => $record->card_title ?: $settings->card_title,
            'eligibility_badge_text' => $record->eligibility_badge_text ?: $settings->eligibility_badge_text,
            'connected_wallet_label' => $record->connected_wallet_label ?: $settings->connected_wallet_label,
            'wallet_display' => $record->wallet_display ?: $record->address,
            'total_refund_label' => $record->total_refund_label ?: $settings->total_refund_label,
            'total_refund_amount' => $record->total_refund_amount ?: $record->formattedTotalRefundAmount(),
            'signature_percentage_label' => $record->signature_percentage_label ?: $settings->signature_percentage_label,
            'signature_percentage_value' => $percentText,
            'refund_status_label' => $record->refund_status_label ?: $settings->refund_status_label,
            'refund_status_value' => $record->refund_status_value ?: 'Pending',
            'signature_progress_label' => $record->signature_progress_label ?: $settings->signature_progress_label,
            'signature_progress_status' => $record->signature_progress_status ?: ($refundedPercent >= 100 ? 'Completed' : 'In progress'),
            'signature_progress_percent' => $percentText,
            'signature_progress_remaining' => $remainingText,
            'progress_percent_number' => $refundedPercent,
        ];

        $s = [
            'card_bg_from' => $record->card_bg_from ?: $settings->card_bg_from,
            'card_bg_to' => $record->card_bg_to ?: $settings->card_bg_to,
            'card_border_color' => $record->card_border_color ?: $settings->card_border_color,
            'badge_bg_color' => $record->badge_bg_color ?: $settings->badge_bg_color,
            'badge_text_color' => $record->badge_text_color ?: $settings->badge_text_color,
            'accent_color' => $record->accent_color ?: $settings->accent_color,
            'progress_bar_color' => $record->progress_bar_color ?: $settings->progress_bar_color,
            'amount_color' => $record->amount_color ?: $settings->amount_color,
            'card_title_font_size' => $record->card_title_font_size ?: ($settings->card_title_font_size ?? '16px'),
            'badge_font_size' => $record->badge_font_size ?: ($settings->badge_font_size ?? '12px'),
            'section_label_font_size' => $record->section_label_font_size ?: ($settings->section_label_font_size ?? '10px'),
            'wallet_value_font_size' => $record->wallet_value_font_size ?: ($settings->wallet_value_font_size ?? '14px'),
            'amount_font_size' => $record->amount_font_size ?: ($settings->amount_font_size ?? '28px'),
            'stat_label_font_size' => $record->stat_label_font_size ?: ($settings->stat_label_font_size ?? '10px'),
            'stat_value_font_size' => $record->stat_value_font_size ?: ($settings->stat_value_font_size ?? '24px'),
            'progress_label_font_size' => $record->progress_label_font_size ?: ($settings->progress_label_font_size ?? '14px'),
            'progress_percent_font_size' => $record->progress_percent_font_size ?: ($settings->progress_percent_font_size ?? '18px'),
            'remaining_font_size' => $record->remaining_font_size ?: ($settings->remaining_font_size ?? '13px'),
        ];

        $html = view('victos-status::victos.partials.status-card', compact('d', 's'))->render();

        return $this->json($request, $tenant, ['found' => true, 'html' => $html]);
    }

    public function widgetConfig(Request $request): JsonResponse
    {
        $tenant = $this->tenantForRequest($request);
        if (!$tenant) {
            return response()->json(['message' => 'This status service is not configured for this website.'], 403)
                ->header('Vary', 'Origin');
        }

        $settings = CardSetting::forUser($tenant);

        return $this->json($request, $tenant, [
            'button' => [
                'text' => $settings->check_status_button_text,
                'textColor' => $settings->check_status_button_text_color,
                'backgroundColor' => $settings->check_status_button_bg_color,
                'fontSize' => $settings->check_status_button_font_size,
            ],
            'modal' => [
                'title' => $settings->modal_header_text,
                'addressLabel' => $settings->modal_address_label_text,
                'submitText' => $settings->modal_submit_text,
            ],
        ]);
    }

    private function tenantForRequest(Request $request): ?Model
    {
        $key = (string) $request->query('tenant_key', '');
        if ($key === '') {
            return null;
        }

        $userModel = config('victos-status.user_model');
        $tenant = $userModel::query()
            ->where('public_tenant_key', $key)
            ->where('is_active', true)
            ->first();

        if (!$tenant || !$this->originIsAllowed($request, $tenant)) {
            return null;
        }

        return $tenant;
    }

    private function originIsAllowed(Request $request, Model $tenant): bool
    {
        $origin = $request->headers->get('Origin');
        $host = $origin ? parse_url($origin, PHP_URL_HOST) : $request->getHost();
        $port = $origin ? parse_url($origin, PHP_URL_PORT) : $request->getPort();
        if (!is_string($host) || $host === '') {
            return false;
        }

        $originHost = strtolower($host) . ($port && !in_array((int) $port, [80, 443], true) ? ':' . $port : '');
        $allowedDomains = $tenant->allowed_domains ?? [];
        if (is_string($allowedDomains)) {
            $allowedDomains = json_decode($allowedDomains, true) ?: [];
        }
        if (!is_array($allowedDomains)) {
            $allowedDomains = [];
        }

        $allowed = collect($allowedDomains)
            ->map(fn ($domain) => $this->normalizeDomain((string) $domain))
            ->filter()
            ->all();

        return in_array($originHost, $allowed, true);
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = trim(strtolower($domain));
        if ($domain === '') {
            return '';
        }

        if (str_contains($domain, '://')) {
            $host = (string) parse_url($domain, PHP_URL_HOST);
            $port = parse_url($domain, PHP_URL_PORT);
            $domain = $host . ($port && !in_array((int) $port, [80, 443], true) ? ':' . $port : '');
        } else {
            $domain = preg_replace('#/.*$#', '', $domain);
        }

        return trim($domain, ". \t\n\r\0\x0B");
    }

    private function json(Request $request, ?Model $tenant, array $data, int $status = 200): JsonResponse
    {
        $response = response()->json($data, $status)->header('Vary', 'Origin');
        if ($tenant && $request->headers->has('Origin')) {
            $response->header('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        }

        return $response;
    }
}
