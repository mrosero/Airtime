/*------------------------------------------------------------------------------

    Copyright (c) 2010 Sourcefabric O.P.S.
 
    This file is part of the Campcaster project.
    http://campcaster.sourcefabric.org/
    To report bugs, send an e-mail to bugs@campware.org
 
    Campcaster is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
  
    Campcaster is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with Campcaster; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 
 
    Author   : $Author: fgerlits $
    Version  : $Revision$
    Location : $URL: svn+ssh://fgerlits@code.campware.org/home/svn/repo/livesupport/branches/scheduler_export/livesupport/src/products/scheduler/src/RpcStopCurrentlyPlayingTest.h $

------------------------------------------------------------------------------*/
#ifndef RpcStopCurrentlyPlayingTest_h
#define RpcStopCurrentlyPlayingTest_h

#ifndef __cplusplus
#error This is a C++ include file
#endif


/* ============================================================ include files */

#ifdef HAVE_CONFIG_H
#include "configure.h"
#endif

#include <cppunit/extensions/HelperMacros.h>

#include "LiveSupport/Core/Ptr.h"
#include "LiveSupport/Core/SessionId.h"

#include "BaseTestMethod.h"

namespace LiveSupport {
namespace Scheduler {

using namespace LiveSupport::Core;

/* ================================================================ constants */


/* =================================================================== macros */


/* =============================================================== data types */

/**
 *  Unit test to test the removeFromSchedule XML-RPC call.
 *
 *  @author  $Author: fgerlits $
 *  @version $Revision$
 *  @see SchedulerDaemon
 */
class RpcStopCurrentlyPlayingTest : public BaseTestMethod
{
    CPPUNIT_TEST_SUITE(RpcStopCurrentlyPlayingTest);
    CPPUNIT_TEST(simpleTest);
    CPPUNIT_TEST(negativeTest);
    CPPUNIT_TEST_SUITE_END();

    private:

        /**
         *  A session ID from the authentication client login() method.
         */
        Ptr<SessionId>::Ref                     sessionId;

        /**
         *  Schedule a playlist, so we can stop it.
         *
         *  @exception CPPUNIT_NS::Exception on test failures.
         */
        void
        schedulePlaylistToPlayNow(void)         throw (CPPUNIT_NS::Exception);


    protected:

        /**
         *  Simple smoke test.
         *
         *  @exception CPPUNIT_NS::Exception on test failures.
         */
        void
        simpleTest(void)                        throw (CPPUNIT_NS::Exception);

        /**
         *  Simple negative test.
         *
         *  @exception CPPUNIT_NS::Exception on test failures.
         */
        void
        negativeTest(void)                      throw (CPPUNIT_NS::Exception);


    public:
        
        /**
         *  Set up the environment for the test case.
         */
        void
        setUp(void)                             throw (CPPUNIT_NS::Exception);

        /**
         *  Clean up the environment after the test case.
         */
        void
        tearDown(void)                          throw (CPPUNIT_NS::Exception);
};


/* ================================================= external data structures */


/* ====================================================== function prototypes */


} // namespace Scheduler
} // namespace LiveSupport

#endif // RpcStopCurrentlyPlayingTest_h
