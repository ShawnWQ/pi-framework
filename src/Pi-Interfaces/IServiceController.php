<?hh

namespace Pi\Interfaces;
use Pi\Interfaces\IRequest;

    /**
     * Responsible for executing the operation within the specified context.
     */
    interface IServiceController
    {
        /// <summary>
        /// Returns the first matching RestPath
        /// </summary>
        public function getRestPathForRequest($httpMethod, $pathInfo) : IRestPath;

        /**
         * Executes the MQ DTO request.
         */
        public function executeMessage(IMessage $mqMessage);

        /**
         * Executes the MQ DTO request with the supplied requestContext
         */
        public function executeMessageWithRequest(IMessage $dto, IRequest $requestContext);

        /**
         * Executes the DTO request under the supplied requestContext.
         *
         */
        public function execute($requestDto, IRequest $request);

        /**
         * Executes the DTO request with an empty RequestContex if $request is null
         */
        public function executeWithEmptyRequest($requestDto);

        /**
         *Executes the DTO request with the current HttpRequest.
         */
        public function executeWithCurrentRequest(IRequest $request);
    }
