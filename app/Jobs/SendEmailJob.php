<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\Utility;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lead_emails = Lead::query()->select('email', 'name')->whereIn('id', $this->data['ids'])->get();

        foreach ($lead_emails as $lead) {
            try {
                // Send email to $lead->email using $this->data['subject'] and $this->data['content']
                // You can use Laravel's Mail facade or any email-sending library of your choice
                Utility::sendEmailTemplate('email_marketing', [$lead->email], $lead->name);

                // Log a message to the console indicating that the email was sent
                $message = "Email sent successfully to {$lead->name} ({$lead->email})";
                \Illuminate\Support\Facades\Log::info($message);
            } catch (\Exception $e) {
                // Log any exceptions that occur during the email sending process
                \Illuminate\Support\Facades\Log::error("Failed to send email to {$lead->name} ({$lead->email}): {$e->getMessage()}");
            }
        }
    }
}
