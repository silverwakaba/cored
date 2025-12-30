<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Core\BaseRequest;
use App\Models\Core\BaseModule;

class BaseRequestSeeder extends Seeder{
    /**
     * Run the database seeds.
     * 
     * This seed data is generated synthetically by AI, adapting to the needs of the document's state.
     * Some data may not be used, but I recommend leaving it untouched. It may be needed at some point.
     */
    public function run() : void{
        // Get module IDs
        $authenticationModule = BaseModule::where('name', 'Authentication')->first();
        $accountManagementModule = BaseModule::where('name', 'Account Management')->first();
        $progressModule = BaseModule::where('name', 'Progress')->first();
        $approvalModule = BaseModule::where('name', 'Approval')->first();
        $paymentModule = BaseModule::where('name', 'Payment')->first();

        BaseRequest::insert([
            // Authentication Module - Verification
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Email Verification",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Phone Verification",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Account Verification",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Identity Verification",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Two Factor Authentication",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Multi Factor Authentication",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "OTP Verification",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "SMS Verification",
            ],
            
            // Authentication Module - Login/Logout
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Login",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Logout",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Force Logout",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Session Expired",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Session Refresh",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Token Refresh",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Remember Me",
            ],
            
            // Authentication Module - Registration
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Registration",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Account Registration",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Registration Confirmation",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Registration Verification",
            ],
            
            // Authentication Module - Password Management
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Password Reset",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Password Change",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Password Recovery",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Forgot Password",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Password Reset Request",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Password Reset Confirmation",
            ],
            
            // Authentication Module - Account Recovery
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Account Recovery",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Account Unlock",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Account Lockout",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Suspicious Activity",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Security Alert",
            ],
            
            // Authentication Module - Social/OAuth
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Social Login",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "OAuth Login",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Google Login",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Facebook Login",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "LinkedIn Login",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Apple Login",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "OAuth Callback",
            ],
            
            // Authentication Module - Security
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Security Check",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Device Verification",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "IP Verification",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Location Verification",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Biometric Authentication",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Fingerprint Authentication",
            ],
            [
                'base_modules_id'   => $authenticationModule->id,
                'name'              => "Face Recognition",
            ],
            
            // Account Management Module - Email Management
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Email Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Email Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Email Confirmation",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Add Email",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Remove Email",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Primary Email Change",
            ],
            
            // Account Management Module - Phone Management
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Phone Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Phone Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Phone Verification",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Add Phone",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Remove Phone",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Primary Phone Change",
            ],
            
            // Account Management Module - Profile Management
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Profile Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Profile Edit",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Name Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Avatar Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Profile Picture Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Bio Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Personal Information Update",
            ],
            
            // Account Management Module - Address Management
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Address Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Address Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Add Address",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Remove Address",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Primary Address Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Billing Address Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Shipping Address Update",
            ],
            
            // Account Management Module - Password Management
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Password Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Password Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Password Strength Update",
            ],
            
            // Account Management Module - Account Status
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Account Activation",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Account Deactivation",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Account Suspension",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Account Unsuspension",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Account Deletion",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Account Restoration",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Account Closure",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Account Reopening",
            ],
            
            // Account Management Module - Settings Management
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Privacy Settings Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Notification Settings Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Security Settings Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Preferences Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Language Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Timezone Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Currency Change",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Theme Change",
            ],
            
            // Account Management Module - Security Management
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Two Factor Authentication Enable",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Two Factor Authentication Disable",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Security Question Update",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Backup Code Generation",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Trusted Device Add",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Trusted Device Remove",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Active Session Management",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Session Revoke",
            ],
            
            // Account Management Module - Connected Accounts
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Social Account Link",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Social Account Unlink",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "OAuth Account Connect",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "OAuth Account Disconnect",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "Third Party Integration",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "API Key Generation",
            ],
            [
                'base_modules_id'   => $accountManagementModule->id,
                'name'              => "API Key Revocation",
            ],
            
            // Progress Module - Initial States
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Draft",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Open",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "New",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Created",
            ],
            
            // Progress Module - Active/In Progress States
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "In Progress",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Active",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Processing",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Pending",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Waiting",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "On Hold",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Suspended",
            ],
            
            // Progress Module - Review/Approval States
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Under Review",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Review",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Approval",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Pending Approval",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Awaiting Approval",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Approved",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Rejected",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Declined",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Not Approved",
            ],
            
            // Progress Module - Completion States
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Completed",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Finished",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Done",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Closed",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Resolved",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Delivered",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Shipped",
            ],
            
            // Progress Module - Cancellation States
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Canceled",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Cancelled",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Abandoned",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Terminated",
            ],
            
            // Progress Module - Error/Failed States
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Failed",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Error",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Expired",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Timeout",
            ],
            
            // Progress Module - Archive/Inactive States
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Archived",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Inactive",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Deleted",
            ],
            
            // Progress Module - Additional Common States
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Assigned",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Unassigned",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Queued",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Scheduled",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Postponed",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Deferred",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Blocked",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Unblocked",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Verified",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Unverified",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Published",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Unpublished",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Locked",
            ],
            [
                'base_modules_id'   => $progressModule->id,
                'name'              => "Unlocked",
            ],
            
            // Approval Module - Review States
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Reviewed",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Under Review",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Pending Review",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Awaiting Review",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "In Review",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Reviewing",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Review Required",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Review Completed",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Review Rejected",
            ],
            
            // Approval Module - Approval States
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Approved",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Pending Approval",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Awaiting Approval",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Conditionally Approved",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Partially Approved",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Auto Approved",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Pre Approved",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Final Approval",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Approval Granted",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Approval Denied",
            ],
            
            // Approval Module - Rejection/Decline States
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Declined",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Rejected",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Not Approved",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Disapproved",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Refused",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Turned Down",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Vetoed",
            ],
            
            // Approval Module - Revision States
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Requires Revision",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Returned for Revision",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Needs Revision",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Revision Requested",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Under Revision",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Revised",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Resubmitted",
            ],
            
            // Approval Module - Additional Approval Workflow States
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Escalated",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Delegated",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Forwarded",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Referred",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "On Hold for Approval",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Approval Expired",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Approval Withdrawn",
            ],
            [
                'base_modules_id'   => $approvalModule->id,
                'name'              => "Approval Cancelled",
            ],
            
            // Payment Module - Payment Status States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Paid",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Unpaid",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Partially Paid",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Fully Paid",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Overpaid",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Pending",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Received",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Confirmed",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Processed",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Completed",
            ],
            
            // Payment Module - Hold/Freeze States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Hold",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "On Hold",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Hold",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Frozen",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Frozen",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Suspended Payment",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Blocked",
            ],
            
            // Payment Module - Failed/Error States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Failed",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Error",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Declined",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Rejected",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Cancelled",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Voided",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Refunded",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Reversed",
            ],
            
            // Payment Module - Processing States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Processing",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Processing",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "In Process",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Awaiting Processing",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Pending Processing",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Being Processed",
            ],
            
            // Payment Module - Due/Overdue States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Due",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Overdue",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Due",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Overdue",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Past Due",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Late Payment",
            ],
            
            // Payment Module - Scheduled/Future States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Scheduled",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Scheduled",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Scheduled Payment",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Future Payment",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Upcoming Payment",
            ],
            
            // Payment Module - Authorization States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Authorized",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Authorization Pending",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Authorization Failed",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Pre Authorized",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Authorization Expired",
            ],
            
            // Payment Module - Settlement States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Settled",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Settlement Pending",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Settlement Completed",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Settlement Failed",
            ],
            
            // Payment Module - Invoice/Billing States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Invoiced",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Invoice Sent",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Invoice Paid",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Invoice Unpaid",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Billed",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Billing Pending",
            ],
            
            // Payment Module - Additional Financial States
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Credited",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Debited",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Charged",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Chargeback",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Disputed",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Disputed",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Expired",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Expired",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Draft Payment",
            ],
            [
                'base_modules_id'   => $paymentModule->id,
                'name'              => "Payment Draft",
            ],
        ]);
    }
}
