<div class="modal fade" id="surveyModal" tabindex="-1" role="dialog" aria-labelledby="surveyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 0; border: none; max-height: 90vh; overflow: hidden;">
            <div class="modal-body p-0" style="display: flex; height: 600px;">
                <!-- Left -->
                <div class="survey-image-section" style="flex: 1; background: linear-gradient(135deg, #e7c328 0%, #b1a232 100%); position: relative; display: flex; align-items: center; justify-content: center;">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.1);"></div>
                    <div style="position: relative; z-index: 1; text-align: center; color: white; padding: 40px;">
                        <svg style="width: 100px; height: 100px; margin: 0 auto 30px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 id="surveyTitleSide" style="font-size: 28px; font-weight: 600; margin-bottom: 15px;">Survey</h3>
                        <p id="surveyDescriptionSide" style="font-size: 16px; opacity: 0.9; max-width: 300px; margin: 0 auto;">Share your thoughts</p>
                    </div>
                </div>
                
                <!-- Right -->
                <div class="survey-content-section" style="flex: 1; padding: 60px 50px; background: #f8f9fa; display: flex; flex-direction: column; justify-content: space-between; overflow-y: auto; position: relative;">
                    <button type="button" class="btn-close-survey"  data-dismiss="modal" aria-label="Close" style="position: absolute; top: 20px; right: 25px; background: none; border: none; font-size: 28px; color: #6c757d; opacity: 0.5; cursor: pointer; padding: 0; width: 30px; height: 30px; line-height: 1;">
                        <span aria-hidden="true" class="close-modal-btn">&times;</span>
                    </button>

                    <form id="surveyForm">
                        <input type="hidden" id="surveyId" name="survey_id">
                        <input type="hidden" id="userId" name="user_id">
                        
                        <div id="surveyQuestionsContainer">
                            <div id="loadingState" style="text-align: center; padding: 40px 0;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p style="margin-top: 20px; color: #6c757d;">Loading survey...</p>
                            </div>
                        </div>

                        <div id="surveyFooter" class="d-flex justify-content-between align-items-center pt-4" style="border-top: 1px solid #dee2e6; display: none !important;">
                            <div class="d-flex" style="gap: 10px;">
                                <button type="button" class="btn" id="prevBtn" style="background: white; color: #495057; border: 1px solid #dee2e6; padding: 10px 28px; font-size: 14px; border-radius: 4px; font-weight: 500; display: none;">
                                    Previous
                                </button>
                                <button type="button" class="btn" id="nextBtn" style="background: #2c3e50; color: white; border: none; padding: 10px 28px; font-size: 14px; border-radius: 4px; font-weight: 500;">
                                    Next
                                </button>
                                <button type="submit" class="btn" id="submitBtn" style="background: #b1a232; color: white; border: none; padding: 10px 28px; font-size: 14px; border-radius: 4px; font-weight: 500; display: none;">
                                    Submit Survey
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
